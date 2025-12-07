<?php

namespace App\services\v1;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use LaravelIdea\Helper\App\Models\_IH_User_C;

class AuthorService extends RequestFilter
{
    protected array $sortable = [
        'name',
        'email',
    ];

    public function id(...$values): Builder
    {
        $result = explode(",", ...$values);

        return $this->builder->whereIn('id', $result);
    }

    public function name(string $value): Builder
    {
        return $this->builder->whereLike('name', '%' . $value . '%');
    }

    public function email(string $value): Builder
    {
        return $this->builder->whereLike('email', '%' . $value . '%');
    }

    public function createdAt(string $dates): Builder
    {
        $results = explode(",", $dates);

        if (count($results) > 1) {
            $this->builder->whereBetween('created_at', $results);
        }

        return $this->builder->whereDate('created_at', $dates);
    }

    public function updatedAt(string $dates): Builder
    {
        $results = explode(",", $dates);

        if (count($results) > 1) {
            $this->builder->whereBetween('updated_at', $results);
        }

        return $this->builder->whereDate('updated_at', $dates);
    }

    public function relationships(...$relations): Builder
    {
        return $this->builder->with($relations);
    }

    public function findUserById(int $id): array|User|_IH_User_C
    {
        return User::findOrFail($id);
    }



    public function createUserTicket(User $author, array $ticketData)
    {
        return Ticket::create([
            'title' =>  Arr::get($ticketData, 'data.attributes.title'),
            'description' => Arr::get($ticketData, 'data.attributes.description'),
            'status' => Arr::get($ticketData, 'data.attributes.status'),
            'user_id' => $author->id
        ]);
    }

}
