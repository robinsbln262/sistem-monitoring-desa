<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Tandai satu notifikasi sebagai sudah dibaca,
     * lalu arahkan ke URL tujuan notifikasi.
     */
    public function read($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notification->update([
            'is_read' => true,
        ]);

        $url = $notification->url ?: route('rekanan.available');

        return redirect($url);
    }

    /**
     * Tandai seluruh notifikasi user sebagai sudah dibaca.
     */
    public function readAll()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
            ]);

        return back()->with('success', 'Semua notifikasi telah ditandai sudah dibaca.');
    }
}
