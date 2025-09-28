<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventAdminController extends Controller
{
    public function index()
    {
        $events = Event::latest()->paginate(15);
        return view('admin.events', compact('events'));
    }

    public function update(Request $request, Event $event)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'location' => 'nullable|string|max:255',
        ]);
        $event->update($data);
        return back()->with('success','Event updated');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return back()->with('success','Event deleted');
    }
}
