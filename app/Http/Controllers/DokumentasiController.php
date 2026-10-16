<?php

namespace App\Http\Controllers;

use App\Models\Dokumentasi;
use App\Models\Proyek;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DokumentasiController extends Controller
{
    public function index()
    {
        // Group dokumentasi berdasarkan group_id - GANTI with('pembangunan') → with('proyek')
        $dokumentasis = Dokumentasi::with('proyek')
            ->select('group_id', 'id_pembangunan', 'keterangan', 'tanggal_upload')
            ->selectRaw('MIN(id_dokumentasi) as id_dokumentasi')
            ->selectRaw('MIN(foto) as foto')
            ->groupBy('group_id', 'id_pembangunan', 'keterangan', 'tanggal_upload')
            ->latest('tanggal_upload')
            ->paginate(12);

        $pembangunans = Proyek::all();

        $dokumentasiBulanIni = Dokumentasi::whereMonth('tanggal_upload', date('m'))
            ->whereYear('tanggal_upload', date('Y'))
            ->distinct('group_id')
            ->count('group_id');

        return view('dokumentasi.index', compact('dokumentasis', 'pembangunans', 'dokumentasiBulanIni'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pembangunan' => 'required|exists:proyek,id_pembangunan',
            'fotos' => 'required|array',
            'fotos.*' => 'image|mimes:jpeg,png,jpg|max:5120',
            'keterangan' => 'nullable',
            'tanggal_upload' => 'required|date',
        ]);

        // Filter base64 image
        $keterangan = $request->keterangan;
        if ($keterangan) {
            $keterangan = preg_replace('/<img[^>]*src="data:image\/[^"]*"[^>]*>/i', '', $keterangan);
            $keterangan = preg_replace('/<p>\s*<\/p>/', '', $keterangan);
            $keterangan = trim($keterangan);
        }

        $groupId = Str::uuid()->toString();
        $uploadCount = 0;

        foreach ($request->file('fotos') as $foto) {
            $fotoPath = $foto->store('dokumentasi', 'public');

            Dokumentasi::create([
                'id_pembangunan' => $request->id_pembangunan,
                'group_id' => $groupId,
                'foto' => $fotoPath,
                'keterangan' => $keterangan,
                'tanggal_upload' => $request->tanggal_upload,
            ]);

            $uploadCount++;
        }

        return redirect()->route('dokumentasi.index')->with('success', $uploadCount.' foto berhasil diupload!');
    }

    public function show($id)
    {
        try {
            // GANTI with('pembangunan') → with('proyek')
            $dokumentasis = Dokumentasi::with('proyek')
                ->where('group_id', $id)
                ->get();

            if ($dokumentasis->isEmpty()) {
                $dokumentasi = Dokumentasi::with('proyek')->find($id);
                if (! $dokumentasi) {
                    return response()->json(['error' => 'Data tidak ditemukan'], 404);
                }
                $dokumentasis = Dokumentasi::with('proyek')
                    ->where('group_id', $dokumentasi->group_id)
                    ->get();
            }

            $firstDokumentasi = $dokumentasis->first();

            return response()->json([
                'group_id' => $firstDokumentasi->group_id,
                'id_pembangunan' => $firstDokumentasi->id_pembangunan,
                'proyek' => $firstDokumentasi->proyek, // GANTI 'pembangunan' → 'proyek'
                'keterangan' => $firstDokumentasi->keterangan,
                'tanggal_upload' => $firstDokumentasi->tanggal_upload,
                'fotos' => $dokumentasis->pluck('foto'),
                'dokumentasi_ids' => $dokumentasis->pluck('id_dokumentasi'),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Data tidak ditemukan: '.$e->getMessage()], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $dokumentasi = Dokumentasi::findOrFail($id);
            $groupId = $dokumentasi->group_id;

            $request->validate([
                'id_pembangunan' => 'required|exists:proyek,id_pembangunan',
                'fotos' => 'nullable|array',
                'fotos.*' => 'image|mimes:jpeg,png,jpg|max:5120',
                'keterangan' => 'nullable',
                'tanggal_upload' => 'required|date',
            ]);

            $keterangan = $request->keterangan;
            if ($keterangan) {
                $keterangan = preg_replace('/<img[^>]*src="data:image\/[^"]*"[^>]*>/i', '', $keterangan);
                $keterangan = preg_replace('/<p>\s*<\/p>/', '', $keterangan);
                $keterangan = trim($keterangan);
            }

            Dokumentasi::where('group_id', $groupId)->update([
                'id_pembangunan' => $request->id_pembangunan,
                'keterangan' => $keterangan,
                'tanggal_upload' => $request->tanggal_upload,
            ]);

            if ($request->hasFile('fotos')) {
                foreach ($request->file('fotos') as $foto) {
                    $fotoPath = $foto->store('dokumentasi', 'public');
                    Dokumentasi::create([
                        'id_pembangunan' => $request->id_pembangunan,
                        'group_id' => $groupId,
                        'foto' => $fotoPath,
                        'keterangan' => $keterangan,
                        'tanggal_upload' => $request->tanggal_upload,
                    ]);
                }
            }

            return redirect()->route('dokumentasi.index')->with('success', 'Dokumentasi berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->route('dokumentasi.index')->with('error', 'Gagal mengupdate dokumentasi: '.$e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $dokumentasis = Dokumentasi::where('group_id', $id)->get();

            if ($dokumentasis->isEmpty()) {
                return redirect()->route('dokumentasi.index')->with('error', 'Data tidak ditemukan');
            }

            foreach ($dokumentasis as $dok) {
                Storage::disk('public')->delete($dok->foto);
                $dok->delete();
            }

            return redirect()->route('dokumentasi.index')->with('success', 'Semua foto berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('dokumentasi.index')->with('error', 'Gagal menghapus dokumentasi');
        }
    }

    public function destroyGroup($groupId)
    {
        try {
            $dokumentasis = Dokumentasi::where('group_id', $groupId)->get();

            foreach ($dokumentasis as $dok) {
                Storage::disk('public')->delete($dok->foto);
                $dok->delete();
            }

            return redirect()->route('dokumentasi.index')->with('success', 'Semua foto berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('dokumentasi.index')->with('error', 'Gagal menghapus dokumentasi');
        }
    }

    public function getData($groupId)
    {
        try {
            $dokumentasis = Dokumentasi::where('group_id', $groupId)->get();

            if ($dokumentasis->isEmpty()) {
                return response()->json(['error' => 'Data tidak ditemukan'], 404);
            }

            $firstDokumentasi = $dokumentasis->first();

            return response()->json([
                'group_id' => $groupId,
                'id_pembangunan' => $firstDokumentasi->id_pembangunan,
                'keterangan' => $firstDokumentasi->keterangan,
                'tanggal_upload' => $firstDokumentasi->tanggal_upload,
                'fotos' => $dokumentasis->pluck('foto'),
                'dokumentasi_ids' => $dokumentasis->pluck('id_dokumentasi'),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Data tidak ditemukan: '.$e->getMessage()], 404);
        }
    }

    public function deletePhoto(Request $request)
    {
        $request->validate([
            'foto' => 'required',
            'group_id' => 'required',
        ]);

        try {
            $dokumentasi = Dokumentasi::where('group_id', $request->group_id)
                ->where('foto', $request->foto)
                ->first();

            if ($dokumentasi) {
                Storage::disk('public')->delete($dokumentasi->foto);
                $dokumentasi->delete();

                return response()->json(['success' => true]);
            }

            return response()->json(['success' => false, 'message' => 'Foto tidak ditemukan'], 404);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function create()
    {
        abort(404);
    }

    public function edit($id)
    {
        abort(404);
    }
}
