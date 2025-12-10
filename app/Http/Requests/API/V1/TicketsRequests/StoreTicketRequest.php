<?php

namespace App\Http\Requests\API\V1\TicketsRequests;

use App\Permissions\Abilities;
use App\Rules\AuthorExists;
use Illuminate\Contracts\Validation\ValidationRule;

class StoreTicketRequest extends BaseTicketRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
         return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'data.attributes.title' => 'required|string',
            'data.attributes.description' => 'required|string',
            'data.attributes.status' => 'required|string|in:A,C,H,X',
            'data.relationships.author.data.id' => ['required', 'integer', new AuthorExists()]
        ];

        if($this->user()->tokenCan(Abilities::CreateOwnTicket))
        {
            $rules['data.relationships.author.data.id'][] = ['in:' , $this->user()->id];
        }

        return $rules;
    }


}
