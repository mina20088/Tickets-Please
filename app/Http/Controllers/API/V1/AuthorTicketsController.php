<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\TicketsRequests\ReplaceTicketRequest;
use App\Http\Requests\API\V1\TicketsRequests\StoreTicketRequest;
use App\Http\Requests\API\V1\TicketsRequests\UpdateTicketRequest;
use App\Http\Resources\V1\TicketsResource;
use App\Models\Ticket;
use App\Models\User;
use App\services\v1\AuthorService;
use App\services\v1\TicketService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;


class AuthorTicketsController extends Controller
{
    public function __construct(
        public TicketService $ticketService,
        public AuthorService $authorService
    ){}

    public function index(int $author_id)
    {
        try{


            $author = $this->authorService->findUserById($author_id);

            $this->authorize("viewAny", [Ticket::class, $author]);

            $tickets = $this->ticketService->getAuthorTickets($author->id);

            return TicketsResource::collection($tickets);

        }catch (ModelNotFoundException) {
            return $this->error("there are no author with the id {$author_id} in our database}", 404);

        }catch (AuthorizationException){
            return $this->error('you are not authorized to view this resource', 401);
        }


    }

    public function store(StoreTicketRequest $request, int $author_id)
    {
        try{
            $author =  $this->authorService->findUserById($author_id);

            $this->authorize('create', [Ticket::class, $author]);

            $ticket = $this->authorService->createUserTicket($author, $request->mappedAttributes());

            return TicketsResource::make($ticket);
        }
        catch (ModelNotFoundException)
        {
            return $this->error("there are no author with the id {$author_id}", 404);

        }catch(AuthorizationException){
            return $this->error('you are not authorized to create a ticket for this author', 401);
        }


    }

    public function replace(ReplaceTicketRequest $request,int $author_id, int $ticket_id)
    {

        try {

            $this->authorize('replace', [Ticket::class]);

            $ticket = $this->ticketService->findTicketById($ticket_id);

            $this->ticketService->update($ticket, $request->mappedAttributes());

            return TicketsResource::make($ticket);

        }catch (ModelNotFoundException){
            return $this->error('there are no ticket with the id {$ticket_id} in our database', 404);
        }catch (AuthorizationException) {
            return $this->error('you are not authorized to replace this resource', 401);
        }

    }

    public function update(UpdateTicketRequest $request, int $author_id, int $ticket_id )
    {
        try {

            $ticket = $this->ticketService->findUsersTicketByUserId($ticket_id, $author_id);

            $this->authorize('update', $ticket);

            $this->ticketService->patch($ticket, $request->mappedAttributes());

            return TicketsResource::make($ticket);

        }catch (ModelNotFoundException){
            return response()->json([
                'error' => "there are no ticket with the id {$ticket_id} in our database"
            ],404);
        }
    }

    public function destroy(int $author_id, Ticket $ticket)
    {
        try{
            $author =  $this->authorService->findUserById($author_id);

            if($ticket->user_id === $author->id){
                $ticket->delete();
                return response()->noContent();
            }

            return response()->json([
                'error' => "you are not authorized to delete this resourse"
            ], ResponseAlias::HTTP_UNAUTHORIZED);
        }
        catch (ModelNotFoundException)
        {
            return response()->json([
                'error' => "there are no author with the id {$author_id} in our database}"
            ], 404);
        }
    }
}
