<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDonationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check(); // Only authenticated users can create donations
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $donationCause = \App\Models\DonationCause::find($this->donation_cause_id);
        $remaining = $donationCause ? ($donationCause->goal_amount - $donationCause->raised_amount) : 0;

        return [
            'amount' => ['required', 'numeric', 'min:0.01', "max:{$remaining}"],
            'donation_cause_id' => ['required', 'exists:donation_causes,id'],
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        $donationCause = \App\Models\DonationCause::find($this->donation_cause_id);
        $remaining = $donationCause ? ($donationCause->goal_amount - $donationCause->raised_amount) : 0;

        return [
            'amount.required' => 'The donation amount is required.',
            'amount.numeric' => 'The donation amount must be a number.',
            'amount.min' => 'The donation amount must be at least $0.01.',
            'amount.max' => 'The donation amount cannot exceed the remaining goal amount of $' . number_format($remaining, 2) . '.',
            'donation_cause_id.required' => 'Please select a donation cause.',
            'donation_cause_id.exists' => 'The selected donation cause is invalid.',
        ];
    }
}