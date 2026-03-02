<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $ticket = $this->resource;

        return [
            'id' => $ticket->id,
            'status' => $ticket->status->value,
            'answered_at' => $ticket->answered_at?->toIso8601String(),
            'created_at' => $ticket->created_at?->toIso8601String(),

            'customer' => [
                'id' => $ticket->customer?->id,
                'name' => $ticket->customer?->name,
                'email' => $ticket->customer?->email,
                'phone_e164' => $ticket->customer?->phone_e164,
            ],

            'subject' => $ticket->subject,
            'message' => $ticket->message,

            'attachments' => $ticket->getMedia('attachments')->map(function ($media) {
                return [
                    'id' => $media->id,
                    'name' => $media->file_name,
                    'mime_type' => $media->mime_type,
                    'size' => $media->size,
                    'url' => $media->getUrl(),
                ];
            })->values(),
        ];
    }
}
