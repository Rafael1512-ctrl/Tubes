<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(20);
        $role = Auth::user()->role;

        // If a role-specific view exists, use it, otherwise default to pasien (or a shared one)
        $viewPath = $role . '.notifications.index';
        if (!view()->exists($viewPath)) {
            $viewPath = 'pasien.notifications.index';
        }

        return view($viewPath, compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return back();
    }
}
