<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Broadcast;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\GeneralNotification;

class BroadcastController extends Controller
{
    public function index()
    {
        $broadcasts = Broadcast::with('author')->orderBy('created_at', 'desc')->get();
        return view('admin.broadcast.index', compact('broadcasts'));
    }

    public function create()
    {
        return view('admin.broadcast.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Title' => 'required|string|max:255',
            'Message' => 'required|string',
            'TargetRole' => 'required|in:all,pasien,dokter,admin',
        ]);

        try {
            $broadcast = Broadcast::create([
                'Title' => $request->Title,
                'Message' => $request->Message,
                'AuthorID' => Auth::id(),
                'TargetRole' => $request->TargetRole,
            ]);

            // Send notifications to users
            $query = User::query();
            if ($request->TargetRole !== 'all') {
                $query->where('role', $request->TargetRole);
            }
            $users = $query->get();

            Notification::send($users, new GeneralNotification(
                'Pengumuman: ' . $request->Title,
                $request->Message,
                'broadcast',
                route('pasien.dashboard')
            ));

            return back()->with('success', 'Broadcast berhasil dikirim ke ' . $users->count() . ' user!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim broadcast: ' . $e->getMessage());
        }
    }
}
