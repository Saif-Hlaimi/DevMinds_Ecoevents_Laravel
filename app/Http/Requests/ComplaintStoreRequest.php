<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ComplaintStoreRequest extends FormRequest {
    public function authorize(){ return auth()->check(); }
    public function rules(){
        return [
            'subject'=>'required|string|max:160',
            'message'=>'required|string|min:5',
'category' => ['required', 'in:general,personal,technical,billing'],
            'priority'=>'nullable|in:low,medium,high',
            'attachment'=>'nullable|file|max:8192',
        ];
    }
}
