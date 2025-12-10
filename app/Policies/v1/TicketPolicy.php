<?php

namespace App\Policies\v1;

use App\Models\Ticket;
use App\Models\User;
use App\Permissions\Abilities;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class TicketPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Ticket $ticket): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $author , User $user): bool
    {
        clock($user);
        if ($author->tokenCan(Abilities::CreateTicket)) {
            return true;
        }
        if($author->tokenCan(Abilities::CreateOwnTicket)){
            return $author->id == $user->id;
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Ticket $ticket): bool
    {
        if ($user->tokenCan(Abilities::UpdateTicket)) {
            return true;
        }
        if ($user->tokenCan(Abilities::UpdateOwnTicket)) {
            return $user->id === $ticket->user_id;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
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

    public function replace(User $user , Ticket $ticket): bool
    {
        if($user->tokenCan(Abilities::ReplaceTicket)){
            return true;
        }
        return false;
    }

}
