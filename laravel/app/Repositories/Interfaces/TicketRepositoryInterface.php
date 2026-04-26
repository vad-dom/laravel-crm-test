<?php

namespace App\Repositories\Interfaces;

use App\Enums\TicketStatus;
use App\Models\Ticket;

interface TicketRepositoryInterface
{
    public function create(int $customerId, string $subject, string $message, TicketStatus $status): Ticket;

    public function sentToday(int $customerId): bool;
}
