<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\ComplaintType;
use Illuminate\Http\Request;
use App\Http\Requests\ComplaintStoreRequest;
use App\Services\BadWordService;
use Illuminate\Support\Facades\Http;
use App\Services\TextRewriterService;
use App\Notifications\ComplaintUpdatedNotification;

class ComplaintController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


public function improveMessage(Request $request, TextRewriterService $rewriter)
{
    $request->validate(['text' => 'required|string']);
    $improved = $rewriter->rewrite($request->input('text'));

    return response()->json(['rewritten' => $improved]);
}


    // Liste avec filtres
    public function index(Request $r)
    {
        $q = Complaint::with('user');

        if ($type = $r->query('type')) {
            $typeId = is_numeric($type)
                ? (int) $type
                : ComplaintType::where('name', $type)->value('id');

            if ($typeId) {
                $q->where('complaint_type_id', $typeId);
            }
        }

        if (auth()->user()->role !== 'admin') {
            $q->where('user_id', auth()->id());
        }

        $complaints = $q->latest()->paginate(12)->withQueryString();

        return view('complaints.index', compact('complaints'));
    }

    // Formulaire création / édition
    public function create(Request $r)
    {
        $this->authorize('create', Complaint::class);

        $types = ComplaintType::orderBy('name')->get();
        $complaint = new Complaint;
        $complaintType = null;

        if ($preset = $r->query('type')) {
            $complaint->complaint_type_id = is_numeric($preset)
                ? (int) $preset
                : ComplaintType::where('name', $preset)->value('id');

            $complaintType = ComplaintType::find($complaint->complaint_type_id);
        }

        return view('complaints.form', compact('complaint', 'types', 'complaintType'));
    }

    public function store(ComplaintStoreRequest $r)
    {
        $data = $r->validated();
        $data['user_id'] = $r->user()->id;
        $data['complaint_type_id'] = $r->input('complaint_type_id');

        if ($r->hasFile('attachment')) {
            $data['attachment_path'] = $r->file('attachment')->store('complaints', 'public');
        }

        // Vérification des bad words
        if (BadWordService::contains($data['message'])) {
            return back()
                ->withInput()
                ->withErrors(['message' => 'Le message contient des mots interdits. Veuillez le corriger.']);
        }

        $complaint = Complaint::create($data);

        return redirect()->route('complaints.index', ['type' => $complaint->complaint_type_id])
                         ->with('success', 'Réclamation ajoutée avec succès !');
    }

    // Affichage d’une réclamation
    public function show(Complaint $complaint)
    {
        $this->authorize('view', $complaint);

        // 🔹 Appel au serveur Flask pour reformuler le message
        try {
            $response = Http::post('http://127.0.0.1:5000/rewrite', [
                'text' => $complaint->message,
            ]);

            if ($response->successful()) {
                $complaint->message = $response->json()['rewritten'] ?? $complaint->message;
            }
        } catch (\Exception $e) {
            // Si erreur, garder le message original
        }

        return view('complaints.show', compact('complaint'));
    }

    public function edit(Complaint $complaint)
    {
        $this->authorize('update', $complaint);
        $types = ComplaintType::orderBy('name')->get();
        return view('complaints.form', compact('complaint', 'types'));
    }

 
// ...

public function update(Request $r, Complaint $complaint)
{
    $this->authorize('update', $complaint);

    $data = $r->validate([
        'subject' => ['sometimes', 'required', 'string', 'max:255'],
        'message' => ['sometimes', 'required', 'string'],
        'category' => ['nullable', 'string', 'max:100'],
        'priority' => ['nullable', 'in:low,medium,high'],
        'status' => ['nullable', 'in:open,pending,resolved,closed'],
        'assigned_to' => ['nullable', 'integer', 'exists:users,id'],
        'complaint_type_id' => ['required', 'exists:complaint_types,id'],
        'attachment' => ['nullable', 'file', 'max:4096'],
    ]);

    if ($r->hasFile('attachment')) {
        $data['attachment_path'] = $r->file('attachment')->store('complaints', 'public');
    }

    $complaint->update($data);

    // ✅ Envoi notification à l'utilisateur concerné
    $complaint->user->notify(new ComplaintUpdatedNotification($complaint));

    return redirect()
        ->route('complaints.show', $complaint)
        ->with('ok', 'Complaint updated and user notified.');
}

    public function destroy(Complaint $complaint)
    {
        $this->authorize('delete', $complaint);
        $complaint->delete();

        return redirect()->route('complaints.index')->with('ok', 'Supprimée.');
    }
}
