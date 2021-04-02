<?php

namespace App\Http\Requests\Address;

use Illuminate\Foundation\Http\FormRequest;

class AddressUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'address_1' => 'required|max:300',
            'address_2' => 'max:300',
            'town' => 'required|max:100',
            'county' => 'required|max:100',
            'postcode' => 'required|max:20',
            'country_id' => 'required',
        ];
    }
}
