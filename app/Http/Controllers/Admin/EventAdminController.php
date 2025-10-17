<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventAdminController extends Controller
{
    /**
     * Affiche tous les événements avec pagination et recherche.
     */
    public function index(Request $request)
    {
        $query = Event::with('user')->latest();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $events = $query->paginate(15)->appends($request->query());

        return view('admin.events', compact('events'));
    }

    /**
     * Affiche le formulaire d’édition pour un événement spécifique.
     */
    public function edit(Event $event)
    {
        return view('admin.events_edit', compact('event'));
    }

    /**
     * Met à jour un événement depuis l’admin dashboard.
     */
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'required|string',
            'date'             => 'required|date',
            'location'         => 'nullable|string|max:255',
            'image'            => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'category'         => 'required|string|max:100',
            'type'             => 'required|in:onsite,online',
            'max_participants' => 'nullable|integer|min:1',
            'meet_link'        => 'nullable|url',
            'is_paid'          => 'required|boolean',
            'price'            => 'nullable|required_if:is_paid,1|numeric|min:0',
        ]);

        if ($request->hasFile('image')) {
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        $event->update($validated);

        return back()->with('success', 'Event updated successfully.');
    }

    /**
     * Supprime un événement.
     */
    public function destroy(Event $event)
    {
        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }

        $event->delete();

        return back()->with('success', 'Event deleted successfully.');
    }

    /**
     * Générer PDF du certificat pour un participant.
     */
    public function certificate(Event $event, $participantId)
    {
        $participant = $event->participants()->where('user_id', $participantId)->first();

        if (!$participant || $participant->pivot->status !== 'approved') {
            abort(403, 'Participant not approved for this event.');
        }

        $html = view('events.certificate', [
            'event' => $event,
            'participant' => $participant,
        ])->render();

        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $filename = 'certificate-'.$event->id.'-'.$participant->id.'.pdf';

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"'
        ]);
    }
}
