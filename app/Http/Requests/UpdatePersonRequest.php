<?php

namespace App\Http\Requests;

use App\Rules\DateOfBirth;
use App\Rules\ZAIDNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePersonRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $personId = $this->route('person')->id;

        return [
            'first_name' => 'required|min:2|max:100',
            'last_name' => 'required|min:2|max:100',
            'id_number' => [new ZAIDNumber(), 'unique:people'],
            'mobile_number' => [
                'required',
                'digits:10',
                'regex:/^0\d{9}$/',
                Rule::unique('people', 'mobile_number')->ignore($personId),
            ],
            'email_address' => [
                'required',
                'email',
                Rule::unique('people', 'email_address')->ignore($personId),
            ],
            'date_of_birth' => [new DateOfBirth($this->input('id_number'))],
            'language_id' => 'required|exists:languages,id',
            'interests' => 'required|array|min:1',
            'interests.*.id' => 'required|integer|exists:interests,id'
        ];
    }

    /**
     * @inheritdoc
     */
    public function messages()
    {
        return [
            'mobile_number.regex' => 'The mobile number must be a valid South African number starting with 0 and 10 digits long.'
        ];
    }
}
