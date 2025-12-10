<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\TicketsRequests\ReplaceTicketRequest;
use App\Http\Requests\API\V1\TicketsRequests\StoreTicketRequest;
use App\Http\Requests\API\V1\TicketsRequests\UpdateTicketRequest;
use App\Http\Resources\V1\TicketsResource;
use App\Models\Ticket;
use App\Policies\v2\TicketPolicy;
use App\services\v1\AuthorService;
use App\services\v1\TicketService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;



class TicketsController extends Controller
{

    protected string  $PolicyClass = TicketPolicy::class;
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
            // Retrieve the author from the request to authorize against.
/*            $authorId = $request->input('data.relationships.author.data.id');

            $author = $this->authorService->findUserById($authorId);*/

            $this->authorize('create', [Ticket::class]);

            $ticket = $this->ticketService->create($request->mappedAttributes());

            return TicketsResource::make($ticket->load('author'));

        } catch (ModelNotFoundException $e) {
            return $this->error("There is no author with the id {$request->input('data.relationships.author.data.id')} in our database.", 404);
        }
        catch (AuthorizationException){
            return $this->error("You don't have permission to create a ticket for this author.", 401);
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
    function update(UpdateTicketRequest $request, int $ticket_id)
    {
        try{
            $ticket = $this->ticketService->findTicketById($ticket_id);

            $this->authorize('update', $ticket);

            $this->ticketService->patch($ticket,$request->mappedAttributes());

            return TicketsResource::make($ticket);

        }
        catch (AuthorizationException){
            return $this->error("You don't have permission to update please refer back to the administrator.", 401);
        }
        catch (ModelNotFoundException){
            return $this->error("there are no ticket with the id {$ticket_id} in our database", 404);
        }
    }

    public function replace(ReplaceTicketRequest $request, int $ticket_id)
    {
        try {
            $ticket = $this->ticketService->findTicketById($ticket_id);

            $this->authorize('replace', $ticket);

            $this->ticketService->update($ticket, $request->mappedAttributes());

            return TicketsResource::make($ticket);

        } catch (ModelNotFoundException) {
            return $this->error("there are no ticket with the id {$ticket_id} in our database", 404);
        }catch (AuthorizationException){
            return $this->error("You don't have permission to replace a ticket for this author.", 401);
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

            $this->authorize('delete', $ticket);

            $ticket->delete();

            return response()->noContent();

        }
        catch (ModelNotFoundException) {
            return $this->error("There is no ticket with the id {$ticket_id} in our database.", 404);
        }
        catch (AuthorizationException){
            return $this->error("You don't have permission to delete a ticket for this author.", 401);
        }


    }
}
