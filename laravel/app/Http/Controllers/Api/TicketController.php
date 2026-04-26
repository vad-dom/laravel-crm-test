<?php

namespace App\Http\Controllers\Api;

use App\DTO\Ticket\CreateTicketData;
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
        $dto = CreateTicketData::fromArray($request->validated());
        $ticket = $this->service->create($dto);
        return new TicketResource($ticket);
    }
}
