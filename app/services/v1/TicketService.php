<?php

namespace App\services\v1;

use App\Models\Ticket;
use App\Models\User;
use http\Client\Response;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TicketService extends RequestFilter
{
    protected array $sortable = [
        'title',
        'status',
        'created_at',
        'updated_at'
    ];
    public function status(...$values): Builder
    {
        $result = explode(",", ...$values );

        return $this->builder->whereIn('status',$result);
    }

    public function title(string $value): Builder
    {
        return $this->builder->whereLike('title', '%' . $value . '%');
    }

    public function createdAt(string $dates): Builder
    {
        $results = explode(",", $dates);

        if(count($results) > 1){
            $this->builder->whereBetween('created_at', $results);
        }

        return$this->builder->whereDate('created_at',$dates);
    }

    public function updatedAt(string $dates): Builder
    {
        $results = explode(",", $dates);

        if(count($results) > 1){
            $this->builder->whereBetween('updated_at', $results);
        }

        return$this->builder->whereDate('updated_at',$dates);
    }

    public function relationships(...$relations): Builder
    {
        return $this->builder->with($relations);
    }


    public function findTicketById(int $id , bool $filter = false): Model|Collection|null
    {
        if($filter){
            return Ticket::filters($this)->findOrFail($id);
        }
        return Ticket::findOrFail($id);
    }

    public function getAuthorTickets(int $user_id): LengthAwarePaginator
    {
        return Ticket::where('user_id', $user_id)
            ->filters($this)
            ->paginate();
    }

    public function create(User $user,array $validatedData)
    {

         return Ticket::create([
             'title' => Arr::get($validatedData, 'data.attributes.title'),
             'description' =>Arr::get($validatedData, 'data.attributes.description'),
             'status' => Arr::get($validatedData, 'data.attributes.status'),
             'user_id' => $user->id,
         ]);

    }

}
