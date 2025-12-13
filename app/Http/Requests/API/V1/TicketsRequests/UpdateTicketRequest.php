<?php

namespace App\Http\Requests\API\V1\TicketsRequests;

use App\Models\Ticket;
use App\Permissions\Abilities;
use App\Rules\AuthorExists;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

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
        //TODO:problem in message appearance two messages appears default and provided in messages
        return [
            'data.attributes.title' => ['sometimes' , 'string'],
            'data.attributes.description' => ['sometimes', 'string' , 'max:1000'],
            'data.attributes.status' => ['sometimes', 'in:A,C,H,X', Rule::prohibitedIf(fn() => $this->user()->tokenCant(Abilities::ReplaceTicket))]
        ];
    }



    public function messages(): array
    {
        return [
            'data.attributes.status.prohibited' => "you are not authorized to update status",
        ];
    }


}
