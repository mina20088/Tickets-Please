<?php

namespace App\Http\Requests\API\V1\TicketsRequests;

use Illuminate\Foundation\Http\FormRequest;


class BaseTicketRequest extends FormRequest
{

    public function mappedAttributes(): array
    {

        $attributeMap = [
            'data.attributes.title' => 'title',
            'data.attributes.description' => 'description',
            'data.attributes.status' => 'status',
            'data.attributes.created_at' => 'created_at',
            'data.attributes.updated_at' => 'updated_at',
            'data.relationships.author.data.id' => 'user_id'
        ];

        $toBeUpdatedAttributes = [];

        foreach ($attributeMap as $key => $attribute)
        {

            if(request()->has($key))
            {
                 $toBeUpdatedAttributes[$attribute] = request()->input($key);
            }
        }

        return $toBeUpdatedAttributes;

    }

    public function messages(): array
    {
        return [
            'data.attributes.status.in' => "the :attribute has to be form this A,C,H,X ",
        ];
    }


}
