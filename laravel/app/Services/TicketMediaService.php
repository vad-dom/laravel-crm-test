<?php

namespace App\Services;

use App\Models\Ticket;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class TicketMediaService
{
    /**
     * @param Ticket $ticket
     * @param array $files
     * @return void
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function attach(Ticket $ticket, array $files): void
    {
        foreach ($files as $file) {
            $ticket->addMedia($file)->toMediaCollection('attachments');
        }
    }
}
