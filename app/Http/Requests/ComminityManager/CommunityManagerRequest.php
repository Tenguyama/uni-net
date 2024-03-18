<?php

namespace App\Http\Requests\ComminityManager;

use Illuminate\Foundation\Http\FormRequest;

class CommunityManagerRequest extends FormRequest
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
            'community_id'=>'required|uuid|exists:communities,id',
            'consumer_id'=>'required|uuid|exists:consumers,id',
        ];
    }
}
