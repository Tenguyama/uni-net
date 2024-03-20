<?php

namespace App\Http\Requests\Complaint;

use App\Enums\ComplaintTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class ComplaintRequest extends FormRequest
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
            'complaintable_id' => [
                'required',
                'uuid',
                function($attribute, $value, $fail) {
                    $type = $this->input('complaintable_type');
                    try {
                        $complaintType = ComplaintTypeEnum::from($type);
                        $model = $complaintType->modelClass();
                        $valid = $model::where('id','=',$value)->exists();
                        if (!$valid) {
                            $fail($attribute.' does not have a valid identifier or type.');
                        }
                    } catch (\ValueError $e) {
                        $fail("Unknown complaint type: $type.");
                    }
                },
            ],
            'complaintable_type' => ['required', new Enum(ComplaintTypeEnum::class)],
            'description' => 'required|string',
        ];
    }
}
