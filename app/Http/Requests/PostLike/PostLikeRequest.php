<?php

namespace App\Http\Requests\PostLike;

use Illuminate\Foundation\Http\FormRequest;

class PostLikeRequest extends FormRequest
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
            'post_id' => 'required|uuid|exists:posts,id',
            'is_liked' => 'required|boolean',
        ];
    }
}
