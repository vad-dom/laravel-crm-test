<?php

namespace App\DTO\Ticket;

use Illuminate\Http\UploadedFile;

readonly class CreateTicketData
{
    /**
     * @param UploadedFile[] $attachments
     */
    public function __construct(
        public string $name,
        public string $email,
        public string $phoneE164,
        public string $subject,
        public string $message,
        public array $attachments = [],
    ) {
    }

    /**
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            phoneE164: $data['phone_e164'],
            subject: $data['subject'],
            message: $data['message'],
            attachments: $data['attachments'] ?? [],
        );
    }
}
