<?php

namespace App\Services;

use App\Models\Ticket;

class TicketStatisticsService
{
    public function get(): array
    {
        $now = now();

        return [
            'day' => Ticket::query()->createdSince($now->copy()->subDay())->count(),
            'week' => Ticket::query()->createdSince($now->copy()->subWeek())->count(),
            'month' => Ticket::query()->createdSince($now->copy()->subMonth())->count(),
        ];
    }
}
