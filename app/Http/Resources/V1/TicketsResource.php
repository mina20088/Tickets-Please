<?php

namespace App\Http\Resources\V1;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Routing\Route;

/**
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $status
 * @property int $user_id
 * @property User $user
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
            'id' => $this->id,
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
                $request->routeIs('tickets.*'),
                [
                    'relationships' =>
                        $this->whenLoaded('user', function () {
                            return [
                                'user' => [
                                    'data' => [
                                        'type' => 'User',
                                        'id' => $this->user->id
                                    ],
                                    'links' => [
                                        'self' => route('users.show', $this->user->id)
                                    ]

                                ],
                            ];
                        })
                ])

        ];
    }
}
