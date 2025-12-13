<?php

namespace App\Http\Requests\API\V1\TicketsRequests;

use App\Permissions\Abilities;
use App\Rules\AuthorExists;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class StoreTicketRequest extends BaseTicketRequest
{

    protected array $rules = [];

    public function authorize(): bool
    {
         return true;
    }

    protected function prepareForValidation(): void
    {
        if($this->routeIs('tickets.store')){

            $this->merge([
                'data.relationships.author.data.id' => $this->input('data.relationships.author.data.id')
            ]);
        }
    }

    public function rules(): array
    {
        $this->rules = [
            'data.attributes.title' => 'required|string',
            'data.attributes.description' => 'required|string',
            'data.attributes.status' => 'required|string|in:A,C,H,X',
        ];

        if($this->routeIs('tickets.store')){
           $this->rules['data.relationships.author.data.id'] = ['required', 'integer', new AuthorExists()];
        }


        return $this->rules;
    }



    public function messages(): array
    {
        return [
            'data.relationships.author.data.id.in' => "you are not authorized to create",
        ];
    }


}
