<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\TicketsRequests\ReplaceTicketRequest;
use App\Http\Requests\API\V1\TicketsRequests\StoreTicketRequest;
use App\Http\Requests\API\V1\TicketsRequests\UpdateTicketRequest;
use App\Http\Resources\V1\TicketsResource;
use App\Models\Ticket;
use App\services\v1\AuthorService;
use App\services\v1\TicketService;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class TicketsController extends Controller
{

    public function __construct(
        protected TicketService $ticketService,
        protected AuthorService $authorService
    )
    {
    }

    public function index()
    {

        $tickets = Ticket::filters($this->ticketService);

        return TicketsResource::collection($tickets->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public
    function store(StoreTicketRequest $request)
    {
        try {

            $ticket = $this->ticketService->create($request->mappedAttributes());

            return TicketsResource::make($ticket->load('author'));

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => "there are no other with the id {$request->input('data.relationships.author.data.id')} in our database}"
            ], 404);
        }

    }

    /**
     * Display the specified resource.
     */
    public
    function show(int $ticket_id)
    {
        try {
            $ticket = $this->ticketService->findTicketById($ticket_id);

            return TicketsResource::make($ticket);

        } catch (ModelNotFoundException) {
            return response()->json(
                [
                    'error' => "there are no ticket with the id {$ticket_id} in our database"
                ],
                404
            );
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public
    function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        //
    }

    public function replace(ReplaceTicketRequest $request, int $ticket_id)
    {
        try {
            $ticket = $this->ticketService->findTicketById($ticket_id);

            $this->ticketService->update($ticket, $request->mappedAttributes());

            return TicketsResource::make($ticket);

        } catch (ModelNotFoundException) {
            return response()->json([
                'error' => "there are no ticket with the id {$ticket_id} in our database"
            ], 404);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public
    function destroy(int $ticket_id)
    {
        try {
            $ticket = $this->ticketService->findTicketById($ticket_id);

            $ticket->delete();

            return response()->noContent();

        } catch (ModelNotFoundException) {
            return response()->json([
                'error' => "there are no ticket with the id {$ticket_id} in our database"
            ], 201);
        }

    }
}
