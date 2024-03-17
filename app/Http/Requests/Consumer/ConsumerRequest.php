<?php

namespace App\Http\Requests\Consumer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ConsumerRequest extends FormRequest
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
            'nickname' => 'sometimes|required|string|unique:consumers,nickname,' . Auth::user()->id,
            'email' => 'sometimes|required|string|email|unique:consumers,email,' . Auth::user()->id,
            'password' => 'nullable|string|min:8',
            'name' => 'required|string',
            'status' => 'nullable|string',
            'description' => 'nullable|string',
            'fakult_id' => 'nullable|exists:fakults,id',
            'group_id' => 'nullable|exists:groups,id',
            'telegram_nickname' => 'nullable|string',
            'is_locked' => 'required|boolean',
        ];

    }
}
