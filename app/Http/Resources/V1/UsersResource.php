<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'type' => 'User',
             'id' => $this->id,
            'attributes' => [
                'name' => $this->name,
                'email' => $this->email,
                $this->mergeWhen(\request()->routeIs('users.*'), [
                    'email_verified_at' => $this->email_verified_at,
                    'created_at' => $this->created_at,
                    'updates_at' => $this->updated_at
                ])
            ],
            'links' => [
                'self' => route('users.show', $this->id)
            ]
        ];
    }
}
