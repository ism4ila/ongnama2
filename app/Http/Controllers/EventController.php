<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        // Afficher les événements à venir en premier, puis les passés, ou paginer tous
        $events = Event::orderBy('start_datetime', 'desc')->paginate(10);
        return view('frontend.events.index', compact('events')); // Vue: resources/views/frontend/events/index.blade.php
    }

    public function show(Event $event) // Route Model Binding
    {
        return view('frontend.events.show', compact('event')); // Vue: resources/views/frontend/events/show.blade.php
    }
}