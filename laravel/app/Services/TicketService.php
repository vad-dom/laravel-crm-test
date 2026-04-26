<?php

namespace App\Services;

use App\DTO\Ticket\CreateTicketData;
use App\Enums\TicketStatus;
use App\Models\Customer;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

readonly class TicketService
{
    private const DAILY_LIMIT_MESSAGE = 'Можно отправлять не более одной заявки в сутки';

    public function __construct(private TicketMediaService $mediaService)
    {
    }

    /**
     * @param CreateTicketData $data
     * @return Ticket
     */
    public function create(CreateTicketData $data): Ticket
    {
        return DB::transaction(function () use ($data): Ticket {
            $customer = $this->resolveCustomer(
                name: $data->name,
                email: $data->email,
                phoneE164: $data->phoneE164,
            );

            $this->ensureDailyLimit($customer);

            $ticket = Ticket::query()->create([
                'customer_id' => $customer->id,
                'subject' => $data->subject,
                'message' => $data->message,
                'status' => TicketStatus::New,
            ]);

            $files = $data->attachments;
            if (!empty($files)) {
                $this->mediaService->attach($ticket, $files);
            }

            return $ticket->load(['customer', 'media']);
        });
    }

    /**
     * @param string $name
     * @param string $email
     * @param string $phoneE164
     * @return Customer
     * @throws ValidationException
     */
    private function resolveCustomer(string $name, string $email, string $phoneE164): Customer
    {
        $byEmail = Customer::query()->where('email', $email)->first();
        if ($byEmail !== null) {
            if ($byEmail->phone_e164 !== $phoneE164) {
                throw ValidationException::withMessages([
                    'phone_e164' => ['Не соответствует номер телефона для клиента с таким email'],
                ]);
            }

            if ($byEmail->name !== $name) {
                $byEmail->name = $name;
                $byEmail->save();
            }

            return $byEmail;
        }

        $byPhone = Customer::query()->where('phone_e164', $phoneE164)->first();
        if ($byPhone !== null) {
            if ($byPhone->email !== $email) {
                throw ValidationException::withMessages([
                    'email' => ['Не соответствует email для клиента с таким номером телефона'],
                ]);
            }

            if ($byPhone->name !== $name) {
                $byPhone->name = $name;
                $byPhone->save();
            }

            return $byPhone;
        }

        return Customer::query()->create([
            'name' => $name,
            'email' => $email,
            'phone_e164' => $phoneE164,
        ]);
    }

    /**
     * @param Customer $customer
     * @return void
     * @throws ValidationException
     */
    private function ensureDailyLimit(Customer $customer): void
    {
        $sentToday = Ticket::query()
            ->where('customer_id', $customer->id)
            ->where('created_at', '>=', now()->startOfDay())
            ->exists();

        if ($sentToday) {
            throw ValidationException::withMessages([
                'email' => [self::DAILY_LIMIT_MESSAGE],
                'phone_e164' => [self::DAILY_LIMIT_MESSAGE],
            ]);
        }
    }
}
