<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\TicketsRequests\ReplaceTicketRequest;
use App\Http\Requests\API\V1\TicketsRequests\StoreTicketRequest;
use App\Http\Resources\V1\TicketsResource;
use App\Models\Ticket;
use App\services\v1\AuthorService;
use App\services\v1\TicketService;
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

            $tickets = $this->ticketService->getAuthorTickets($author->id);

            return TicketsResource::collection($tickets);

        }catch (ModelNotFoundException) {

            return response()->json([
                'error' => "there are no author with the id {$author_id} in our database}"
            ], 404);
        }


    }

    public function store(int $author_id, StoreTicketRequest $request)
    {
        try{
            $author =  $this->authorService->findUserById($author_id);

            $ticket = $this->authorService->createUserTicket($author, $request->mappedAttributes());

            return TicketsResource::make($ticket);
        }
        catch (ModelNotFoundException)
        {
            return response()->json([
                'error' => "there are no author with the id {$author_id} in our database}"
            ], 404);
        }


    }

    public function replace(int $author_id, int $ticket_id, ReplaceTicketRequest $request)
    {

        try {
            $ticket = $this->ticketService->findTicketById($ticket_id);

            if($author_id !== $ticket->user_id){
                return response()->json([
                    'error' => "you are not authorized to update this resource"
                ]);
            }

            $this->ticketService->update($ticket, $request->mappedAttributes());

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
