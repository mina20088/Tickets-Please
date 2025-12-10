<?php

namespace App\Http\Requests\API\V1\TicketsRequests;

use App\Rules\AuthorExists;
use App\services\v1\RequestFilter;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTicketRequest extends BaseTicketRequest
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
            'data.attributes.title' => ['sometimes' , 'string'],
            'data.attributes.description' => ['sometimes', 'string' , 'max:1000'],
            'data.attributes.status' => ['sometimes', 'in:A,C,H,X']
        ];

        if(request()->routeIs('authors.tickets.*'))
        {
             $rules = array_merge($rules, [
                 'data.relationships.author.data.id' => ['sometimes', new AuthorExists()],
             ]);
        }

        return $rules;
    }
}
