<?php

namespace App\Policies;

use App\Models\Complaint;
use App\Models\User;
use Illuminate\Auth\Access\Response;

 class ComplaintPolicy {
    public function view(User $u, Complaint $c){
    return $u->role === 'admin' || $c->user_id === $u->id;
}

public function update(User $u, Complaint $c){
    return $u->role === 'admin' || $c->user_id === $u->id;
}

public function delete(User $u, Complaint $c){
    return $u->role === 'admin' || $c->user_id === $u->id;
}
    public function create(User $u){ return (bool) $u; }
}
