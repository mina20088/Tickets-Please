<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\StoreTicketRequest;
use App\Http\Requests\API\V1\UpdateTicketRequest;
use App\Http\Resources\V1\TicketsResource;
use App\Models\Ticket;
use App\Models\User;
use App\services\v1\AuthorService;
use App\services\v1\TicketService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;


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
        try{
            $user = $this->authorService->findUserById($request->input('data.relationships.author.data.id'));
        }catch (ModelNotFoundException $e){
            return response()->json([
                'error' => "there are no other with the id {$request->input('data.relationships.author.data.id')} in our database}"
            ], 404);
        }

        $ticket = $this->ticketService->create($user,$request->validated());

        return TicketsResource::make($ticket->load('author'));

    }

    /**
     * Display the specified resource.
     */
    public
    function show(int $ticket_id)
    {
        try
        {
            $ticket = $this->ticketService->findTicketById($ticket_id);
        } catch (ModelNotFoundException)
        {
            return response()->json(
                [
                    'error' => "there are no ticket with the id {$ticket_id} in our database"
                ],
                404
            );
        }
        return TicketsResource::make($ticket);
    }

    /**
     * Update the specified resource in storage.
     */
    public
    function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public
    function destroy(int $ticket_id)
    {
        try {
            $ticket = $this->ticketService->findTicketById($ticket_id);
        } catch (ModelNotFoundException) {
            return response()->json([
                'error' => "there are no ticket with the id {$ticket_id} in our database"
            ], 201);
        }

        $ticket->delete();
        return response()->noContent();
    }
}
