<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class NotificationController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render('notifications/index', ['notifications' => $request->user()->notifications()->latest()->paginate(20)->through(fn ($notification) => ['id' => $notification->id, 'type' => class_basename($notification->type), 'data' => $notification->data, 'read_at' => $notification->read_at?->toISOString(), 'created_at' => $notification->created_at->toISOString()])]);
    }

    public function read(Request $request, string $notification): RedirectResponse
    {
        $request->user()->notifications()->findOrFail($notification)->markAsRead();

        return back();
    }

    public function readAll(Request $request): RedirectResponse
    {
        $request->user()->unreadNotifications->markAsRead();

        return back();
    }
}
