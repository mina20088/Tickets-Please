<?php

namespace App\Http\Resources\V1;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @property string $email_verified_at
 * @property string $created_at
 * @property string $updated_at
 * @property string $name
 * @property string $email
 * @property int $id
 * @property Ticket $tickets
 */
class UsersResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'Author',
            'id' => $this->id,
            'attributes' => [
                'name' => $this->name,
                'email' => $this->email,
                $this->mergeWhen(\request()->routeIs('authors.*'), [
                    'email_verified_at' => $this->email_verified_at,
                    'created_at' => $this->created_at,
                    'updates_at' => $this->updated_at
                ])
            ],
            $this->mergeWhen($request->routeIs('authors.*'), [
                'links' => [
                    'self' => route('authors.show', $this->id),
                ],
                'relationships' => [
                    'tickets' => $this->whenLoaded('tickets', function(){
                        return [
                            'data' => $this->tickets->pluck('id')->map(function ($id) {
                                return [
                                    'type' => 'Ticket',
                                    'id' => $id
                                ];
                            }),
                            'links' => [
                                'self' => $this->tickets->pluck('id')->map(function ($id) {
                                    return route('tickets.show', $id);
                                }),
                            ]
                        ];
                    }),
                ],
                'included' => [
                    'tickets' => TicketsResource::collection($this->whenLoaded('tickets')),
                ]
            ]),




        ];
    }
}
