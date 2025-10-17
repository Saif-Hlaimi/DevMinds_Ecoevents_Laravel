<?php

namespace App\Http\Controllers;

use App\Models\CommentReaction;
use App\Models\Event;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use App\Notifications\EventCreatedNotification;
use App\Notifications\EventPaymentNotification;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use OpenAI\Laravel\Facades\OpenAI;

use Dompdf\Dompdf;
use Dompdf\Options;




class EventController extends Controller
{
   public function index(Request $request)
{
    $query = Event::with(['user', 'participants'])->latest();

    // ✅ Gestion My Events / All Events
    if ($request->has('my_events')) {
        // 👉 Affiche uniquement MES événements
        $query->where('user_id', auth()->id());
    } else {
        // 👉 Affiche uniquement les événements des AUTRES utilisateurs
        if (auth()->check()) {
            $query->where('user_id', '!=', auth()->id());
        }
    }

    // 🔎 Recherche
    if ($request->filled('search')) {
        $search = $request->input('search');
        $filter = $request->input('filter');

        if ($filter === 'title') {
            $query->where('title', 'like', "%$search%");
        } elseif ($filter === 'date') {
            $query->whereDate('date', $search);
        } elseif ($filter === 'location') {
            $query->where('location', 'like', "%$search%");
        } else {
            $query->where('title', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%");
        }
    }

    // 📅 Filtre par date de publication
    if ($request->filled('date_posted') && $request->date_posted !== 'all') {
        $days = (int) $request->date_posted;
        $query->where('created_at', '>=', now()->subDays($days));
    }

    // 📍 Filtre par localisation
    if ($request->has('location') && is_array($request->location)) {
        $query->whereIn('location', $request->location);
    }

    // 💰 Filtre par prix
    if ($priceFilter = $request->input('price_filter')) {
        if ($priceFilter === 'free') {
            $query->where(function ($q) {
                $q->where('is_paid', false)
                  ->orWhereNull('price')
                  ->orWhere('price', 0);
            });
        } elseif ($priceFilter === 'paid') {
            $query->where('is_paid', true)->where('price', '>', 0);
        } elseif ($priceFilter === 'below_20') {
            $query->where('is_paid', true)->where('price', '<', 20);
        }
    }

    // ⏰ Afficher uniquement les événements à venir (optionnel)
    // ->active seulement si tu veux cacher les passés
    // $query->where('date', '>', now());

    $events = $query->paginate(9)->appends($request->query());

    return view('events.index', compact('events'));
}

    public function show(Event $event)
    {
        $event->load(['user', 'participants', 'comments.user']);
        return view('events.show', compact('event'));
    }

    public function create()
    {
        $categories = Event::categories();
        return view('events.create', compact('categories'));
    }

     public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date|after:now',
            'location' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'category' => 'required|in:' . implode(',', array_keys(Event::categories())),
            'type' => 'required|in:onsite,online',
            'max_participants' => 'nullable|integer|min:1',
            'meet_link' => 'nullable|url',
            'is_paid' => 'required|boolean',
            'price' => 'nullable|required_if:is_paid,1|numeric|min:0',
        ]);

        $validated['user_id'] = Auth::id();

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        $event = Event::create($validated);

        // 🔔 Notification à tous les utilisateurs
        $users = User::all();
        Notification::send($users, new EventCreatedNotification($event));

        return redirect()->route('events.index')->with('success', 'Event created successfully.');
    }


    public function edit(Event $event)
    {
        if (Auth::id() !== $event->user_id && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $categories = Event::categories();
        return view('events.edit', compact('event', 'categories'));
    }

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
            'category'         => 'required|in:' . implode(',', array_keys(Event::categories())),
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

    public function unregister(Event $event)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in.');
        }

        $event->participants()->detach(Auth::id());

        return back()->with('success', 'You have unregistered from the event.');
    }
     public function process(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);

        if (!$event->is_paid || $event->price <= 0) {
            return redirect()->route('events.show', $event)
                ->with('error', 'This event is free. No payment needed.');
        }

        // Simulation d’un paiement (en vrai ici tu intègrerais Stripe/PayPal/etc.)
        // Tu peux imaginer qu’on valide toujours le paiement pour l’instant.
        $paymentSuccess = true;

        if ($paymentSuccess) {
            // On enregistre la participation automatique
            $event->participants()->attach(Auth::id(), ['status' => 'approved']);

            return redirect()->route('events.show', $event)
                ->with('success', 'Payment successful! You are now registered for this event.');
        } else {
            return redirect()->route('events.show', $event)
                ->with('error', 'Payment failed. Please try again.');
        }
    }
     public function requestParticipation(Event $event)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to request participation.');
        }

        if ($event->participants()->where('user_id', Auth::id())->exists()) {
            return back()->with('error', 'You have already requested participation.');
        }

        if ($event->type === 'onsite' && $event->participants()->wherePivot('status', 'approved')->count() >= $event->max_participants) {
            return back()->with('error', 'This event is already full.');
        }

        $event->participants()->attach(Auth::id(), ['status' => 'pending']);

        return back()->with('success', 'Your participation request has been sent.');
    }
public function approve(Event $event, $userId)
    {
        if (Auth::id() !== $event->user_id && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $event->participants()->updateExistingPivot($userId, ['status' => 'approved']);

        return back()->with('success', 'Participation approved.');
    }

    /**
     * Rejeter un participant (organisateur/admin).
     */
    public function reject(Event $event, $userId)
    {
        if (Auth::id() !== $event->user_id && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $event->participants()->updateExistingPivot($userId, ['status' => 'rejected']);

        return back()->with('success', 'Participation rejected.');
    }
    // Afficher le formulaire de paiement
public function showPaymentForm(Event $event)
{
    if (!$event->is_paid || $event->price <= 0) {
        return redirect()->route('events.show', $event)->with('error', 'This event is free.');
    }

    return view('events.payment', compact('event'));
}

public function processPayment(Request $request, Event $event)
{
    if (!$event->is_paid || $event->price <= 0) {
        return redirect()->route('events.show', $event)->with('error', 'This event is free.');
    }

    if ($event->participants()->where('user_id', Auth::id())->exists()) {
        return redirect()->route('events.show', $event)->with('error', 'You already joined this event.');
    }

    Stripe::setApiKey(config('services.stripe.secret'));

    $session = StripeSession::create([
        'payment_method_types' => ['card'],
        'mode' => 'payment',
        'line_items' => [[
            'price_data' => [
                'currency' => config('services.stripe.currency', 'usd'),
                'product_data' => [
                    'name' => $event->title,
                    'description' => substr($event->description, 0, 200),
                ],
                'unit_amount' => (int) round($event->price * 100),
            ],
            'quantity' => 1,
        ]],
        'customer_email' => Auth::user()->email,
        'success_url' => route('events.payment.success', $event) . '?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url'  => route('events.show', $event),
        'metadata' => [
            'event_id' => $event->id,
            'user_id' => Auth::id(),
        ],
    ]);

    return redirect($session->url);
}

  public function paymentSuccess(Request $request, Event $event)
    {
        $sessionId = $request->query('session_id');
        Stripe::setApiKey(config('services.stripe.secret'));

        if ($sessionId) {
            $session = \Stripe\Checkout\Session::retrieve($sessionId);

            if ($session->payment_status === 'paid') {
                $userId = $session->metadata->user_id ?? Auth::id();

                $event->participants()->syncWithoutDetaching([
                    $userId => ['status' => 'approved']
                ]);

                // 🔔 Notification à l’utilisateur
                $user = User::find($userId);
                $user->notify(new EventPaymentNotification($event));

                return redirect()->route('events.show', $event)
                    ->with('success', '✅ Payment successful! You are now registered.');
            }
        }

        return redirect()->route('events.show', $event)
            ->with('error', '❌ Payment could not be confirmed.');
    }

public function storeComment(Request $request, Event $event)
{
    $request->validate([
        'content' => 'required|string|min:3|max:1000',
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

    if (auth()->id() !== $comment->user_id && auth()->user()->role !== 'admin') {
        abort(403, 'Unauthorized action.');
    }

    $comment->delete();

    return back()->with('success', 'Comment deleted successfully.');
}
public function reactComment(Comment $comment, $type) 
{ 
    $user = Auth::user(); 
    if (!$user)
     return redirect()->route('login'); 
    $reaction = $comment->reactions()->where('user_id', $user->id)->first(); 
    if ($reaction) {
         if ($reaction->type === $type)
             $reaction->delete(); 
            else $reaction->update(['type' => $type]); }
             else { CommentReaction::create(['comment_id'=>$comment->id,'user_id'=>$user->id,'type'=>$type]);
             } 
             return back(); 
}



public function certificate(Event $event, User $participant)
{
    // Vérifier si le participant est inscrit et approuvé
    $isParticipant = $event->participants()
        ->where('user_id', $participant->id)
        ->wherePivot('status', 'approved')
        ->exists();

    if (!$isParticipant) {
        abort(403, 'Participant not approved for this event.');
    }

    // Render Blade view en HTML
    $html = view('events.certificate', [
        'event' => $event,
        'participant' => $participant,
    ])->render();

    // Configurer Dompdf
    $options = new Options();
    $options->set('isRemoteEnabled', true); // autoriser images distantes
    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape'); // paysage pour certificat
    $dompdf->render();

    $filename = 'certificate-'.$event->id.'-'.$participant->id.'.pdf';

    return response($dompdf->output(), 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'attachment; filename="'.$filename.'"'
    ]);
}


}


    // Les autres méthodes (comments, react, approve/reject, etc.) restent identiques

