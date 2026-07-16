<?php

namespace App\Http\Controllers\Api\Widget;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Ticket\CreateTicketService;

class TicketController extends Controller
{
    protected $createTicketService;

    public function __construct(CreateTicketService $createTicketService)
    {
        $this->createTicketService = $createTicketService;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|string|max:255",
            "email" => "required|email|max:255",
            "subject" => "required|string|max:255",
            "description" => "required|string",
            "priority" => "nullable|in:low,normal,high,urgent",
        ]);

        $result = $this->createTicketService->execute($validated, tenant("id"));

        return response()->json([
            "message" => "Ticket created successfully.",
            "ticket_id" => $result["ticket"]->id,
            "token" => $result["token"]
        ], 201);
    }
}
