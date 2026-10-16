<?php

namespace App\Http\Controllers;

use App\Models\Proyek;
use Illuminate\Support\Facades\Log;

class PublikController extends Controller
{
    public function index()
    {
        $pembangunans = Proyek::with([
            'dokumentasis',
            'monitorings' => function ($query) {
                $query->orderBy('tanggal_monitoring', 'desc');
            },
            'komentarMasyarakatApproved',
        ])
            ->where('is_published', 1)
            ->latest()
            ->paginate(9);

        return view('publik.index', compact('pembangunans'));
    }

    public function show($id)
    {
        try {
            $pembangunan = Proyek::with([
                'monitorings' => function ($query) {
                    $query->orderBy('tanggal_monitoring', 'desc');
                },
                'dokumentasis' => function ($query) {
                    $query->orderBy('tanggal_upload', 'desc');
                },
                'rekananProyek' => function ($q) {
                    $q->where('status_penawaran', 'diterima')
                        ->orWhere('status', 'setuju');
                },
                'rekananProyek.rekanan',
                'user',
                'komentarMasyarakatApproved' => function ($query) {
                    $query->orderBy('tanggal_komentar', 'desc');
                },
            ])
                ->where('is_published', 1)
                ->findOrFail($id);

            // Ambil rekanan terpilih
            $rekananTerpilih = null;
            $hargaDeal = null;

            if ($pembangunan->rekanan_terpilih) {
                $rekananProyek = $pembangunan->rekananProyek
                    ->where('id_rekanan', $pembangunan->rekanan_terpilih)
                    ->first();

                if ($rekananProyek) {
                    $hargaDeal = $rekananProyek->harga_penawaran_rekanan;
                    $rekananTerpilih = $rekananProyek->rekanan;
                }
            }

            return view('publik.show', compact('pembangunan', 'hargaDeal', 'rekananTerpilih'));

        } catch (\Exception $e) {
            Log::error('Error showing proyek: '.$e->getMessage(), [
                'id' => $id,
                'trace' => $e->getTraceAsString(),
            ]);
            abort(404, 'Proyek tidak ditemukan');
        }
    }
}
