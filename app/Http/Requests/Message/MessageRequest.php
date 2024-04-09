<?php

namespace App\Http\Requests\Message;

use Illuminate\Foundation\Http\FormRequest;

class MessageRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'sometimes|required|uuid|exists,messages:id',
            'chat_id' => 'required|uuid|exists,chats:id',
            'body' => 'required|string',
            'media' => 'sometimes|required|url',
            //або
            //'media' => 'nullable|url|regex:/\.(jpeg|jpg|gif|png)$/i',

        ];
    }
}
