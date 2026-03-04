<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TicketStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateTicketStatusRequest;
use App\Models\Ticket;
use App\Services\Admin\TicketStatusService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminTicketController extends Controller
{
    /**
     * @param Request $request
     * @return Factory|View
     */
    public function index(Request $request): Factory|View
    {
        $tickets = Ticket::query()
            ->with('customer')
            ->latest()
            ->filter($request)
            ->paginate(10)
            ->withQueryString();

        return view('admin.tickets.index', [
            'tickets' => $tickets,
            'statuses' => TicketStatus::cases(),
            'selectedStatuses' => (array) $request->input('status', []),
            'email' => (string) $request->input('email', ''),
            'phone' => (string) $request->input('phone', ''),
            'from' => (string) $request->input('from', ''),
            'to' => (string) $request->input('to', ''),
        ]);
    }

    /**
     * @param Ticket $ticket
     * @return Factory|View
     */
    public function show(Ticket $ticket): Factory|View
    {
        $ticket->load(['customer', 'media']);
        return view('admin.tickets.show', compact('ticket'));
    }

    /**
     * @param UpdateTicketStatusRequest $request
     * @param Ticket $ticket
     * @param TicketStatusService $service
     * @return RedirectResponse
     */
    public function updateStatus(
        UpdateTicketStatusRequest $request,
        Ticket $ticket,
        TicketStatusService $service
    ): RedirectResponse {
        $status = $request->enum('status', TicketStatus::class);
        $service->update($ticket, $status);
        return back()->with('status', 'Статус обновлен');
    }
}
