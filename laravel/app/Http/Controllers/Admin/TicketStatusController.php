<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TicketStatusController extends Controller
{
    public function __invoke(Request $request, Ticket $ticket): RedirectResponse
    {
        return redirect()
            ->route('admin.tickets.show', $ticket);
    }
}
