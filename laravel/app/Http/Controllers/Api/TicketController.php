<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreTicketRequest;
use App\Http\Resources\TicketResource;
use App\Services\TicketService;

class TicketController extends Controller
{
    public function __construct(private readonly TicketService $service)
    {
    }

    /**
     * @param StoreTicketRequest $request
     * @return TicketResource
     */
    public function store(StoreTicketRequest $request): TicketResource
    {
        $data = $request->validated();
        $ticket = $this->service->create($data);
        return new TicketResource($ticket);
    }
}
