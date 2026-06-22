<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class ProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_date' => ['nullable', 'date'],
            'deadline' => [
                'nullable',
                'date',
                function (string $attribute, $value, callable $fail): void {
                    if ($this->filled('start_date') && Carbon::parse($value)->lt(Carbon::parse($this->input('start_date')))) {
                        $fail('The deadline must be a date after or equal to the start date.');
                    }
                },
            ],
        ];
    }
}
