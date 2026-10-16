<?php

namespace App\Http\Controllers;

use App\Models\KomentarMasyarakat;
use App\Models\Proyek;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class KomentarMasyarakatController extends Controller
{
    /**
     * Store komentar dari masyarakat
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id_pembangunan' => 'required|exists:proyek,id_pembangunan',
                'nama' => 'required|string|max:100',
                'email' => 'nullable|email|max:100',
                'komentar' => 'required|string|min:5|max:1000',
                'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:5120', // Maksimal 5MB
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error_komentar', 'Mohon periksa kembali data yang Anda masukkan.');
            }

            $proyek = Proyek::where('id_pembangunan', $request->id_pembangunan)
                ->where('is_published', 1)
                ->first();

            if (! $proyek) {
                return redirect()->back()
                    ->with('error_komentar', 'Proyek tidak ditemukan atau tidak dipublikasikan.');
            }

            // Logika Penyimpanan Foto (Mengikuti pola Profile / Upload CV)
            $namaFoto = null;
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $namaFoto = 'komentar_'.uniqid().'_'.time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('uploads/komentar_masyarakat'), $namaFoto);
            }

            // Simpan komentar ke database
            $komentar = KomentarMasyarakat::create([
                'id_pembangunan' => $request->id_pembangunan,
                'nama' => $request->nama,
                'email' => $request->email,
                'komentar' => $request->komentar,
                'foto' => $namaFoto,
                'status' => 'pending',
                'tanggal_komentar' => now(),
            ]);

            Log::info('Komentar masyarakat ditambahkan', [
                'id_komentar' => $komentar->id_komentar,
                'id_pembangunan' => $request->id_pembangunan,
                'nama' => $request->nama,
            ]);

            return redirect()->back()
                ->with('success_komentar', 'Terima kasih! Komentar Anda telah terkirim dan akan ditampilkan setelah disetujui oleh admin.');

        } catch (\Exception $e) {
            Log::error('Error menyimpan komentar masyarakat: '.$e->getMessage(), [
                'data' => $request->all(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error_komentar', 'Terjadi kesalahan. Silakan coba lagi nanti.')
                ->withInput();
        }
    }

    /**
     * Admin - List semua komentar
     */
    public function indexAdmin()
    {
        $komentars = KomentarMasyarakat::with('proyek')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'total' => KomentarMasyarakat::count(),
            'pending' => KomentarMasyarakat::pending()->count(),
            'approved' => KomentarMasyarakat::approved()->count(),
        ];

        return view('admin.komentar-masyarakat.index', compact('komentars', 'stats'));
    }

    /**
     * Admin - Approve komentar
     */
    public function approve($id)
    {
        try {
            $komentar = KomentarMasyarakat::findOrFail($id);
            $komentar->status = 'approved';
            $komentar->save();

            Log::info('Komentar masyarakat disetujui', [
                'id_komentar' => $id,
                'admin' => auth()->user()->nama_user,
            ]);

            return redirect()->back()
                ->with('success', 'Komentar berhasil disetujui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menyetujui komentar: '.$e->getMessage());
        }
    }

    /**
     * Admin - Reject komentar
     */
    public function reject($id)
    {
        try {
            $komentar = KomentarMasyarakat::findOrFail($id);
            $komentar->status = 'rejected';
            $komentar->save();

            Log::info('Komentar masyarakat ditolak', [
                'id_komentar' => $id,
                'admin' => auth()->user()->nama_user,
            ]);

            return redirect()->back()
                ->with('success', 'Komentar berhasil ditolak.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menolak komentar: '.$e->getMessage());
        }
    }

    /**
     * Admin - Delete komentar
     */
    public function destroy($id)
    {
        try {
            $komentar = KomentarMasyarakat::findOrFail($id);
            $komentar->delete();

            Log::info('Komentar masyarakat dihapus', [
                'id_komentar' => $id,
                'admin' => auth()->user()->nama_user,
            ]);

            return redirect()->back()
                ->with('success', 'Komentar berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus komentar: '.$e->getMessage());
        }
    }

    /**
     * Get komentar by proyek (AJAX)
     */
    public function getByProyek($id)
    {
        try {
            $komentars = KomentarMasyarakat::where('id_pembangunan', $id)
                ->where('status', 'approved')
                ->orderBy('tanggal_komentar', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $komentars->map(function ($k) {
                    return [
                        'id_komentar' => $k->id_komentar,
                        'nama' => $k->nama,
                        'komentar' => $k->komentar,
                        'tanggal' => $k->tanggal_komentar->locale('id')->translatedFormat('d F Y H:i'),
                        'created_at' => $k->created_at->format('Y-m-d H:i:s'),
                    ];
                }),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Gagal memuat komentar',
            ], 500);
        }
    }
}
