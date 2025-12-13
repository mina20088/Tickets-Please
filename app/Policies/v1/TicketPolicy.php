<?php

namespace App\Policies\v1;

use App\Models\Ticket;
use App\Models\User;
use App\Permissions\Abilities;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class TicketPolicy
{

    public function viewAny(User $user, ?User $author = null): bool
    {
        if ($user->tokenCan(Abilities::ListOwnTickets)) {
            return $author && $user->id === $author->id;
        }

        return $user->tokenCan('ticket:view-any'); // Example admin permission
    }
    public function create(User $author, ?User $user): bool
    {
        if ($author->tokenCan(Abilities::CreateTicket)) {
            return true;
        }
        if($author->tokenCan(Abilities::CreateOwnTicket)){
             return  $author->id === $user->id;
        }
        return false;
    }

    public function update(User $user, Ticket $ticket): bool
    {
        if ($user->tokenCan(Abilities::UpdateTicket)) {
            clock('worked:UpdateTicket');
            return true;
        }
        elseif ($user->tokenCan(Abilities::UpdateOwnTicket)){
            return $user->id === $ticket->user_id;
        }

        return false;
    }

    public function delete(User $user, Ticket $ticket): bool
    {
         if($user->tokenCan(Abilities::DeleteTicket)){
             return true;
         }

         if($user->tokenCan(Abilities::DeleteOwnTicket)){
             return $user->id === $ticket->user_id;
         }

         return false;
    }

    public function replace(User $user): bool
    {
        if($user->tokenCan(Abilities::ReplaceTicket)){
             return true;
        }
        return false;
    }

}
