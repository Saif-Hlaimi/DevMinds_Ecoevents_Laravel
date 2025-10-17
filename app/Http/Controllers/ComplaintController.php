<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\ComplaintType;
use Illuminate\Http\Request;
use App\Http\Requests\ComplaintStoreRequest;

class ComplaintController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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

    // Formulaire création
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

    // Sauvegarde
   public function store(ComplaintStoreRequest $r)
{
    $data = $r->validated();

    // Forcer la valeur du type
    $data['complaint_type_id'] = $r->input('complaint_type_id');

    if ($r->hasFile('attachment')) {
        $data['attachment_path'] = $r->file('attachment')->store('complaints', 'public');
    }

    $data['user_id'] = $r->user()->id;

    $complaint = Complaint::create($data);

    // Redirection corrigée : on passe bien l'objet ou l'id
    return redirect()
        ->route('complaint-types.show', $complaint->complaint_type_id)
        ->with('success', 'Réclamation ajoutée avec succès.');
        $request->validate([
        'message' => 'required|string|max:5000',
    ]);

    // Vérifier si le message contient des mots interdits
    if (BadWordService::containsBadWords($request->message)) {
        return back()->withErrors([
            'message' => 'Votre message contient des mots inappropriés. Veuillez le modifier.'
        ]);
    }

    // Sinon, enregistrer le message
    Complaint::create([
        'message' => $request->message,
        'user_id' => auth()->id(),
        // autres champs...
    ]);

    return back()->with('success', 'Réclamation ajoutée avec succès !');
}


    public function show(Complaint $complaint)
    {
        $this->authorize('view', $complaint);
        return view('complaints.show', compact('complaint'));
    }

    public function edit(Complaint $complaint)
    {
        $this->authorize('update', $complaint);
        $types = ComplaintType::orderBy('name')->get();
        return view('complaints.form', compact('complaint', 'types'));
    }

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

        return redirect()->route('complaints.show', $complaint)->with('ok', 'Mise à jour effectuée.');
    }

    public function destroy(Complaint $complaint)
    {
        $this->authorize('delete', $complaint);
        $complaint->delete();

        return redirect()->route('complaints.index')->with('ok', 'Supprimée.');
    }
}
