<?php

namespace App\Http\Controllers;

use App\Models\Proyek;
use App\Models\RekananProyek;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class RekananController extends Controller
{
    // ==================== REKANAN - DASHBOARD ====================

    public function dashboard()
    {
        $userId = Auth::id();

        $proyekTersedia = Proyek::where('is_published', true)
            ->where('status_penawaran', 'open')
            ->count();

        $proyekDisetujui = RekananProyek::where('id_rekanan', $userId)
            ->where('status_penawaran', 'diterima')
            ->count();

        $proyekDitinjau = RekananProyek::where('id_rekanan', $userId)
            ->where('status', 'meninjau')
            ->count();

        $monitoringAktif = Proyek::whereHas('rekananProyek', function ($q) use ($userId) {
            $q->where('id_rekanan', $userId)
                ->where('status_penawaran', 'diterima');
        })
            ->where('status', 'berjalan')
            ->count();

        $publishedProyek = Proyek::where('is_published', true)
            ->where('status_penawaran', 'open')
            ->with([
                'user',
                'rekananProyek' => function ($q) use ($userId) {
                    $q->where('id_rekanan', $userId);
                }
            ])
            ->latest()
            ->take(5)
            ->get();

        // Notifikasi proyek baru
        $lastSeenProjectTime = session(
            'rekanan_last_seen_project_time',
            now()->subYears(10)
        );

        $proyekBaruCount = Proyek::where('is_published', true)
            ->where('status_penawaran', 'open')
            ->where('created_at', '>', $lastSeenProjectTime)
            ->count();

        return view('rekanan.dashboard', compact(
            'proyekTersedia',
            'proyekDisetujui',
            'proyekDitinjau',
            'monitoringAktif',
            'publishedProyek',
            'proyekBaruCount'
        ));
    }


    // ==================== REKANAN - LIHAT PROYEK TERSEDIA ====================

    public function available()
    {
        $lastSeenProjectTime = session(
            'rekanan_last_seen_project_time',
            now()->subYears(10)
        );

        $proyekBaruCount = Proyek::where('is_published', true)
            ->where('status_penawaran', 'open')
            ->where('created_at', '>', $lastSeenProjectTime)
            ->count();

        $proyeks = Proyek::where('is_published', true)
            ->where(function ($q) {
                $q->where('status_penawaran', 'open')
                    ->orWhereHas('rekananProyek', function ($r) {
                        $r->where('id_rekanan', Auth::id())
                            ->where('status', 'setuju');
                    });
            })
            ->with([
                'user',
                'rekananProyek' => function ($q) {
                    $q->where('id_rekanan', Auth::id());
                }
            ])
            ->latest()
            ->paginate(9);

        session([
            'rekanan_last_seen_project_time' => now()
        ]);

        return view('rekanan.available', compact(
            'proyeks',
            'proyekBaruCount'
        ));
    }


    // ==================== REKANAN - AJUKAN BID/HARGA ====================

    public function apply(Request $request, $id)
    {
        $request->validate([
            'harga_penawaran' => 'required|numeric|min:1000',
            'catatan' => 'nullable|min:5',
        ]);

        $proyek = Proyek::findOrFail($id);

        if ($proyek->status_penawaran != 'open') {
            return redirect()
                ->back()
                ->with('error', 'Proyek sudah tidak menerima penawaran!');
        }

        $existing = RekananProyek::where('id_proyek', $id)
            ->where('id_rekanan', Auth::id())
            ->first();

        if ($existing) {
            $existing->update([
                'status' => 'meninjau',
                'catatan' => $request->catatan,
                'harga_penawaran_rekanan' => $request->harga_penawaran,
                'status_penawaran' => 'pending',
                'tanggal_respon' => now(),
            ]);
        } else {
            RekananProyek::create([
                'id_proyek' => $id,
                'id_rekanan' => Auth::id(),
                'status' => 'meninjau',
                'catatan' => $request->catatan,
                'harga_penawaran_rekanan' => $request->harga_penawaran,
                'status_penawaran' => 'pending',
                'tanggal_respon' => now(),
            ]);
        }

        return redirect()
            ->back()
            ->with(
                'success',
                'Penawaran Anda telah dikirim! Tunggu konfirmasi dari Admin.'
            );
    }


    // ==================== REKANAN - PROYEK SAYA ====================

    public function myProjects()
    {
        $proyeks = Proyek::whereHas('rekananProyek', function ($q) {
            $q->where('id_rekanan', Auth::id())
                ->where(function ($r) {
                    $r->where('status_penawaran', 'diterima')
                        ->orWhere('status', 'setuju');
                });
        })
            ->with([
                'user',
                'monitorings',
                'evaluasiRekanan' => function ($q) {
                    $q->where('id_rekanan', Auth::id());
                },
            ])
            ->latest()
            ->paginate(10);

        return view('rekanan.my-projects', compact('proyeks'));
    }


    // ==================== REKANAN - SETUJU/TOLAK (LEGACY) ====================

    public function approve(Request $request, $id)
    {
        return redirect()
            ->back()
            ->with(
                'error',
                'Gunakan fitur "Ajukan Penawaran" untuk mengikuti proyek.'
            );
    }


    public function reject(Request $request, $id)
    {
        $existing = RekananProyek::where('id_proyek', $id)
            ->where('id_rekanan', Auth::id())
            ->first();

        if ($existing) {
            $existing->update([
                'status' => 'tolak',
                'status_penawaran' => 'ditolak',
                'catatan' => $request->catatan,
            ]);
        }

        return redirect()
            ->back()
            ->with('success', 'Anda menolak proyek ini.');
    }


    // ==================== REKANAN - UPLOAD CV ====================

    public function uploadCv(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validasi CV
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'cv' => [
                'required',
                'file',
                'mimes:pdf,doc,docx',
                'max:5120',
            ],
        ], [
            'cv.required' => 'Silakan pilih file CV terlebih dahulu.',
            'cv.file' => 'File CV tidak valid.',
            'cv.mimes' => 'CV harus berformat PDF, DOC, atau DOCX.',
            'cv.max' => 'Ukuran CV maksimal 5 MB.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Ambil user yang sedang login
        |--------------------------------------------------------------------------
        */

        $user = User::findOrFail(Auth::id());

        /*
        |--------------------------------------------------------------------------
        | Folder penyimpanan
        |--------------------------------------------------------------------------
        |
        | File disimpan langsung ke:
        |
        | public/uploads/cv_rekanan
        |
        */

        $folder = public_path('uploads/cv_rekanan');

        /*
        |--------------------------------------------------------------------------
        | Buat folder jika belum ada
        |--------------------------------------------------------------------------
        */

        if (!File::exists($folder)) {
            File::makeDirectory(
                $folder,
                0755,
                true
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Hapus CV lama
        |--------------------------------------------------------------------------
        */

        if (!empty($user->cv)) {

            // Ambil hanya nama file
            $oldFilename = basename($user->cv);

            $oldFile = $folder . DIRECTORY_SEPARATOR . $oldFilename;

            if (File::exists($oldFile)) {
                File::delete($oldFile);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Generate nama file baru
        |--------------------------------------------------------------------------
        */

        $extension = $request
            ->file('cv')
            ->getClientOriginalExtension();

        $filename = uniqid('cv_', true)
            . '_' . time()
            . '.' . $extension;

        /*
        |--------------------------------------------------------------------------
        | Simpan file
        |--------------------------------------------------------------------------
        */

        $request
            ->file('cv')
            ->move($folder, $filename);

        /*
        |--------------------------------------------------------------------------
        | Simpan NAMA FILE saja ke database
        |--------------------------------------------------------------------------
        */

        $user->update([
            'cv' => $filename,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->back()
            ->with(
                'success',
                'CV berhasil diupload!'
            );
    }


    // ==================== REKANAN - HAPUS CV ====================

    public function deleteCv()
    {
        /*
        |--------------------------------------------------------------------------
        | Ambil user
        |--------------------------------------------------------------------------
        */

        $user = User::findOrFail(Auth::id());

        /*
        |--------------------------------------------------------------------------
        | Cek apakah memiliki CV
        |--------------------------------------------------------------------------
        */

        if (empty($user->cv)) {
            return redirect()
                ->back()
                ->with(
                    'error',
                    'CV tidak ditemukan.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Lokasi folder CV
        |--------------------------------------------------------------------------
        */

        $folder = public_path('uploads/cv_rekanan');

        $filename = basename($user->cv);

        $file = $folder . DIRECTORY_SEPARATOR . $filename;

        /*
        |--------------------------------------------------------------------------
        | Hapus file fisik
        |--------------------------------------------------------------------------
        */

        if (File::exists($file)) {
            File::delete($file);
        }

        /*
        |--------------------------------------------------------------------------
        | Hapus nama file dari database
        |--------------------------------------------------------------------------
        */

        $user->update([
            'cv' => null,
        ]);

        return redirect()
            ->back()
            ->with(
                'success',
                'CV berhasil dihapus!'
            );
    }


    // ==================== KEPALA DESA - STATUS REKANAN ====================

    public function statusForKades()
    {
        $rekananProyek = RekananProyek::with([
            'proyek',
            'rekanan'
        ])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $totalRekanan = User::where(
            'level',
            'rekanan'
        )->count();

        $totalSetuju = RekananProyek::where(
            'status_penawaran',
            'diterima'
        )->count();

        $totalTolak = RekananProyek::where(
            'status_penawaran',
            'ditolak'
        )->count();

        $totalMeninjau = RekananProyek::where(
            'status_penawaran',
            'pending'
        )->count();

        return view(
            'kades.rekanan-status',
            compact(
                'rekananProyek',
                'totalRekanan',
                'totalSetuju',
                'totalTolak',
                'totalMeninjau'
            )
        );
    }
}