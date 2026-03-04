<?php

namespace App\Services\Admin;

use App\Enums\TicketStatus;
use App\Models\Ticket;

class TicketStatusService
{
    /**
     * @param Ticket $ticket
     * @param TicketStatus $newStatus
     * @return void
     */
    public function update(Ticket $ticket, TicketStatus $newStatus): void
    {
        $ticket->status = $newStatus;

        if ($newStatus === TicketStatus::Closed && $ticket->answered_at === null) {
            $ticket->answered_at = now();
        }

        $ticket->save();
    }
}
