<?php

namespace App\Http\Requests\Follow;

use App\Enums\FollowTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class FollowRequest extends FormRequest
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
            'trackable_id' => [
                'required',
                'uuid',
                function($attribute, $value, $fail) {
                    $type = $this->input('trackable_type');
                    try {
                        $complaintType = FollowTypeEnum::from($type);
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
            'trackable_type' => ['required', new Enum(FollowTypeEnum::class)],

        ];
    }
}
