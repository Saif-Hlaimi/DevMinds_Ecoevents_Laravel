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

    public function messages(): array
    {
        return [
            'title.required' => 'The title is required.',
            'title.string' => 'The title must be a string.',
            'title.max' => 'The title may not be greater than 255 characters.',
            'description.required' => 'The description is required.',
            'description.string' => 'The description must be a string.',
            'image.image' => 'The image must be an image.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
            'image.max' => 'The image may not be greater than 2048 kilobytes.',
            'raised_amount.numeric' => 'The raised amount must be a number.',
            'raised_amount.min' => 'The raised amount must be at least 0.',
            'goal_amount.required' => 'The goal amount is required.',
            'goal_amount.numeric' => 'The goal amount must be a number.',
            'goal_amount.min' => 'The goal amount must be at least 0.01.',
            'sdg.required' => 'The SDG is required.',
            'sdg.string' => 'The SDG must be a string.',
            'sdg.max' => 'The SDG may not be greater than 255 characters.',
        ];
    }
}