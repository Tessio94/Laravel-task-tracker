<?php

namespace App\Http\Requests;

use App\Enums\TaskFrequency;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateRecurringTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->can('manage', $this->recurring_task);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string', 'max:255'],
            'category_id'  => ['nullable'],
            'frequency'    => ['required', Rule::enum(TaskFrequency::class)],
            'days'         => ['exclude_unless:frequency,weekly', 'required', 'array', 'min:1'],
            'days.*'       => ['string', Rule::in(['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'])],
            'day_of_month' => ['exclude_unless:frequency,monthly', 'required', 'integer', 'between:1,31'],
            'start_date'   => ['nullable', 'date'],
            'end_date'     => ['nullable', 'date', 'after_or_equal:start_date'],
        ];
    }
}
