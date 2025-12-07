<?php

namespace App\Http\Resources\V1;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $status
 * @property int $user_id
 * @property User $author
 */
class TicketsResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'Ticket',
            'id' =>  $this->id,
            'attributes' => [
                'title' => $this->title,
                $this->mergeWhen(
                    $request->routeIs('tickets.*'),
                    [
                        'description' => $this->description
                    ]
                ),
                'status' => $this->status
            ],
            'links' => [
                'self' => route('tickets.show', $this->id)
            ],
            $this->mergeWhen(
                $request->routeIs('tickets.*')||
                $request->routeIs('authors.tickets.*'),
                [
                    'relationships' => $this->whenLoaded('author', function(){
                        return [
                            'author' => [
                                'data' => [
                                    'type' => 'Author',
                                    'id' => $this->author->id
                                ],
                                'links' => [
                                    'self' => route('authors.show', $this->author->id)
                                ]
                            ]

                        ];
                    },[]),
                    'included' => [
                        'author' => UsersResource::make($this->whenLoaded('author'))
                    ]
                ])

        ];
    }
}
