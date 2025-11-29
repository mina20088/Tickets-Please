<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Routing\Route;

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
            'id' => $this->id,
            'attributes' => [
                'title' => $this->title,
                'description' => $this->description,
                'status' => $this->status
            ],
            'links' => [
                'self' => route('tickets.show', $this->id)
            ],
            'relationships' => [
                'user' => [
                    'data' => [
                        'type' => 'user',
                        'id' => (string)$this->user_id,
                    ],
                    'links' => [
                        'self' => 'todo'
                    ],

                ]
            ],
            'includes' => [
                'user' => new UsersResource($this->user)
            ]
        ];
    }
}
