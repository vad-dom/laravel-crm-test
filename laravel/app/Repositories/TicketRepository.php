<?php

namespace App\Repositories;

use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Repositories\Interfaces\TicketRepositoryInterface;

class TicketRepository implements TicketRepositoryInterface
{
    /**
     * @param int $customerId
     * @param string $subject
     * @param string $message
     * @param TicketStatus $status
     * @return Ticket
     */
    public function create(int $customerId, string $subject, string $message, TicketStatus $status): Ticket
    {
        return Ticket::query()->create([
            'customer_id' => $customerId,
            'subject' => $subject,
            'message' => $message,
            'status' => $status,
        ]);
    }

    /**
     * @param int $customerId
     * @return bool
     */
    public function sentToday(int $customerId): bool
    {
        return Ticket::query()
            ->where('customer_id', $customerId)
            ->where('created_at', '>=', now()->startOfDay())
            ->exists();
    }
}
