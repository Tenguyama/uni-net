<?php

namespace App\Http\Requests\Post;

use App\Enums\PostTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class PostRequest extends FormRequest
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
            'postable_id' => [
                'required',
                'uuid',
                function($attribute, $value, $fail) {
                    $type = $this->input('trackable_type');
                    try {
                        $complaintType = PostTypeEnum::from($type);
                        $model = $complaintType->modelClass();
                        $valid = $model::where('id','=',$value)->exists();
                        if (!$valid) {
                            $fail($attribute.' does not have a valid identifier or type.');
                        }
                    } catch (\ValueError $e) {
                        $fail("Unknown follow type: $type.");
                    }
                },
            ],
            'postable_type' => ['required', new Enum(PostTypeEnum::class)],
            'theme_id'=>'required|uuid|exists:themes,id',
            'description' => 'required|string',
            'media' => 'sometimes|required|url',
            //або
            //'media' => 'nullable|url|regex:/\.(jpeg|jpg|gif|png)$/i',
        ];
    }
}
