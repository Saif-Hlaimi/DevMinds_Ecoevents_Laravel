<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ComplaintUpdateRequest extends FormRequest {
    public function authorize(){ return auth()->user()?->isAdmin() ?? false; }
    public function rules(){
        return [
            'status'=>'nullable|in:open,pending,resolved,closed',
            'priority'=>'nullable|in:low,medium,high',
            'assigned_to'=>'nullable|exists:users,id',
        ];
    }
}