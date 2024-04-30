<?php

namespace App\Http\Requests\Community;

use Illuminate\Foundation\Http\FormRequest;

class CommunityRequest extends FormRequest
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
            'id' => 'sometimes|required|uuid|exists,communities,id',
            'nickname' => 'required|string|unique:communities,nickname,' . ($this->id ?? 'NULL'),
            'description' => 'nullable|string',
            'is_locked' => 'required|boolean',
            'avatar' => 'sometimes|required|url',
            //або
            //'avatar' => 'nullable|url|regex:/\.(jpeg|jpg|gif|png)$/i',
        ];
    }
}
