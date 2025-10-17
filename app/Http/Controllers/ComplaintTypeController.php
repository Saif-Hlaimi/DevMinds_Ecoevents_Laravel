<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\ComplaintType;

class ComplaintTypeController extends Controller
{
    // Liste des types
    public function index()
    {
        $types = ComplaintType::withCount('complaints')
            ->orderBy('name')
            ->get();

        return view('complaint_types.index', compact('types'));
    }

    // Affichage d’un type + ses réclamations
    public function show(ComplaintType $complaintType)
    {
        $complaints = Complaint::with('user')
            ->where('complaint_type_id', $complaintType->id)
            ->latest()
            ->paginate(9);

        return view('complaint_types.show', compact('complaintType', 'complaints'));
    }
}
