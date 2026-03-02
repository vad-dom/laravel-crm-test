<?php

namespace App\Services;

use App\Models\Ticket;
use Carbon\Carbon;

class TicketStatisticsService
{
    public function get(): array
    {
        $now = Carbon::now();

        return [
            'day' => Ticket::query()->createdSince($now->copy()->subDay())->count(),
            'week' => Ticket::query()->createdSince($now->copy()->subWeek())->count(),
            'month' => Ticket::query()->createdSince($now->copy()->subMonth())->count(),
        ];
    }
}
