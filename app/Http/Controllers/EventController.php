<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Display a listing of events with optional search & filter.
     */
  public function index(Request $request)
{
    $query = Event::with(['user', 'participants'])->latest();

    // Recherche par mot-clé et filtre
    if ($request->filled('search') && $request->filled('filter')) {
        $search = $request->input('search');
        $filter = $request->input('filter');

        $query->when($filter === 'title', fn($q) => 
            $q->where('title', 'like', "%{$search}%")
        )->when($filter === 'date', fn($q) => 
            $q->whereDate('date', $search)
        )->when($filter === 'location', fn($q) => 
            $q->where('location', 'like', "%{$search}%")
        );
    }

    // Filtre événements à venir
    $query->when($request->has('upcoming'), fn($q) => 
        $q->where('date', '>', now())
    );

    // Mes événements
    $query->when($request->has('my_events') && auth()->check(), fn($q) => 
        $q->where('user_id', auth()->id())
    );

    // Catégorie
    $query->when($request->filled('category'), fn($q) => 
        $q->where('category', $request->category)
    );

    // Type (online / onsite)
    $query->when($request->filled('type'), fn($q) => 
        $q->whereIn('type', (array) $request->type)
    );

    // 🔹 Filtre par date_posted
    if ($request->filled('date_posted') && $request->date_posted !== 'all') {
        $days = (int) $request->date_posted;
        $query->where('created_at', '>=', now()->subDays($days));
    }

    // 🔹 Filtre par location (cases cochées)
    if ($request->has('location') && is_array($request->location)) {
        $query->whereIn('location', $request->location);
    }

    $events = $query->paginate(9)->appends($request->query());

    return view('events.index', compact('events'));
}

    /**
     * Display a single event with participants and comments.
     */
    public function show(Event $event)
    {
        $event->load(['user', 'participants', 'comments.user']);
        return view('events.show', compact('event'));
    }

    /**
     * Show the form for creating a new event.
     */
    public function create()
    {
        $categories = Event::categories();
        return view('events.create', compact('categories'));
    }

    /**
     * Store a newly created event.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'required|string',
            'date'             => 'required|date|after:now',
            'location'         => 'nullable|string|max:255',
            'image'            => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'category'         => 'required|in:' . implode(',', array_keys(Event::categories())),
            'type'             => 'required|in:onsite,online',
            'max_participants' => 'nullable|integer|min:1',
            'meet_link'        => 'nullable|url',
        ]);

        $validated['user_id'] = Auth::id();

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        Event::create($validated);

        return redirect()->route('events.index')->with('success', 'Event created successfully.');
    }

    /**
     * Show the form for editing an event.
     */
    public function edit(Event $event)
    {
        if (Auth::id() !== $event->user_id && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $categories = Event::categories();
        return view('events.edit', compact('event', 'categories'));
    }

    /**
     * Update an existing event.
     */
    public function update(Request $request, Event $event)
    {
        if (Auth::id() !== $event->user_id && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'required|string',
            'date'             => 'required|date|after:now',
            'location'         => 'nullable|string|max:255',
            'image'            => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'type'             => 'required|in:onsite,online',
            'category'         => 'required|in:' . implode(',', array_keys(Event::categories())),
            'max_participants' => 'nullable|integer|min:1',
            'meet_link'        => 'nullable|url',
        ]);

        if ($request->hasFile('image')) {
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        $event->update($validated);

        return redirect()->route('events.index')->with('success', 'Event updated successfully.');
    }

    /**
     * Delete an event.
     */
    public function destroy(Event $event)
    {
        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }

        $event->delete();

        return redirect()->route('events.index')->with('success', 'Event deleted successfully.');
    }

    /**
     * Register a participant for an event.
     */
    public function register(Event $event)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to register.');
        }

        if ($event->participants()->where('user_id', Auth::id())->exists()) {
            return back()->with('error', 'You are already registered for this event.');
        }

        if ($event->type === 'onsite' && $event->participants()->count() >= $event->max_participants) {
            return back()->with('error', 'This event is full.');
        }

        $event->participants()->attach(Auth::id());

        return back()->with('success', 'You have successfully registered for the event.');
    }

    /**
     * Cancel registration.
     */
    public function unregister(Event $event)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in.');
        }

        $event->participants()->detach(Auth::id());

        return back()->with('success', 'You have unregistered from the event.');
    }

    /**
     * Store a comment for the event (only non-organizer participants).
     */
  public function storeComment(Request $request, Event $event)
{
    $request->validate([
        'content' => 'required|string|max:1000',
    ]);

    $event->comments()->create([
        'user_id' => auth()->id(),
        'content' => $request->content,
    ]);

    return back()->with('success', 'Comment posted successfully.');
}

public function destroyComment($commentId)
{
    $comment = \App\Models\Comment::findOrFail($commentId);

    if(auth()->id() !== $comment->user_id && auth()->user()->role !== 'admin'){
        abort(403, 'Unauthorized action.');
    }

    $comment->delete();

    return back()->with('success', 'Comment deleted successfully.');
}

}
