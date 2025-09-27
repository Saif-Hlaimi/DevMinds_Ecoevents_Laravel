<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDonationCauseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'raised_amount' => ['nullable', 'numeric', 'min:0'],
            'goal_amount' => ['required', 'numeric', 'min:0.01'],
            'sdg' => ['required', 'string', 'max:255'],
        ];
    }
}