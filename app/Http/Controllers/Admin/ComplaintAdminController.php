<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\User;
use Illuminate\Http\Request;
use Stichoza\GoogleTranslate\GoogleTranslate;

class ComplaintAdminController extends Controller
{
    // Afficher toutes les réclamations
    public function index(Request $request)
    {
        $filters = $request->only(['q', 'status', 'priority']);
        
        $complaints = Complaint::with(['user', 'assignee', 'type'])
            ->filter($filters) // scope dans le modèle
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.complaints.index', [
            'complaints' => $complaints,
            'filters' => $filters,
        ]);
    }

    // Voir une réclamation
    public function show(Complaint $complaint)
    {
        return view('admin.complaints.show', compact('complaint'));
    }

    // Modifier une réclamation
    public function edit(Complaint $complaint)
    {
        $users = User::where('role', 'admin')->pluck('name', 'id'); // pour l’assignation
        return view('admin.complaints.edit', compact('complaint', 'users'));
    }

    // Mettre à jour
    public function update(Request $request, Complaint $complaint)
    {
        $data = $request->validate([
            'status' => ['nullable', 'in:open,pending,resolved,closed'],
            'priority' => ['nullable', 'in:low,medium,high'],
            'assigned_to' => ['nullable', 'exists:users,id'],
        ]);

        $complaint->update($data);

        return redirect()
            ->route('admin.complaints.index')
            ->with('success', 'Réclamation mise à jour.');
    }

    // Supprimer
    public function destroy(Complaint $complaint)
    {
        $complaint->delete();
        return back()->with('success', 'Réclamation supprimée.');
    }



    public function translate(Complaint $complaint, Request $request)
{
    $lang = $request->query('lang', 'fr'); // par défaut français
    $tr = new GoogleTranslate($lang);

    $translatedMessage = $tr->translate($complaint->message);

    return response()->json([
        'translated' => $translatedMessage,
        'lang' => $lang,
    ]);
}
}
