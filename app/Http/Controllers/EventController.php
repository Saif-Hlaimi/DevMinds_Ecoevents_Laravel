<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('user')->latest()->get();
        return view('events.index', compact('events'));
    }

    public function show(Event $event)
    {
        return view('events.show', compact('event'));
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'date' => ['required', 'date', 'after:now'],
            'location' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $validated['user_id'] = Auth::id();

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        Event::create($validated);

        return redirect()->route('events.index')->with('success', 'Event created successfully.');
    }

    public function edit(Event $event)
    {
        if (Auth::id() !== $event->user_id && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }
        return view('events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        if (Auth::id() !== $event->user_id && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'date' => ['required', 'date', 'after:now'],
            'location' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
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

    public function destroy(Event $event)
    {
        

        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }

        $event->delete();

        return redirect()->route('events.index')->with('success', 'Event deleted successfully.');
    }
}