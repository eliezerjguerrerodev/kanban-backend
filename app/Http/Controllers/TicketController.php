<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->tickets()->orderBy('order')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|string',
            'order' => 'nullable|integer'
        ]);

        $ticket = $request->user()->tickets()->create($validated);
        return response()->json($ticket, 201);
    }

    public function update(Request $request, Ticket $ticket)
    {
        if ($ticket->user_id !== $request->user()->id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|string',
            'order' => 'sometimes|integer'
        ]);

        $ticket->update($validated);
        return response()->json($ticket);
    }

    public function destroy(Request $request, Ticket $ticket)
    {
        if ($ticket->user_id !== $request->user()->id) {
            abort(403);
        }
        
        $ticket->delete();
        return response()->noContent();
    }
}
