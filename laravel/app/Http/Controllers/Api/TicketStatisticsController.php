<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TicketStatisticsResource;
use App\Services\TicketStatisticsService;

class TicketStatisticsController extends Controller
{
    public function __construct(private readonly TicketStatisticsService $service)
    {
    }

    public function __invoke(): TicketStatisticsResource
    {
        return new TicketStatisticsResource($this->service->get());
    }
}
