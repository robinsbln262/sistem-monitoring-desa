<?php

namespace App\Http\Controllers;

use App\Models\PengeluaranProyek;
use App\Models\Proyek;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengeluaranProyekController extends Controller
{
    private function checkAdmin()
    {
        if (! Auth::check() || Auth::user()->level !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
    }

    public function index($id)
    {
        $this->checkAdmin();

        $proyek = Proyek::with([
            'pengeluarans' => function ($query) {
                $query->orderBy('tanggal_pengeluaran', 'desc')
                    ->orderBy('id_pengeluaran', 'desc');
            },
            'rekananSetuju',
        ])->findOrFail($id);

        $pengeluarans = $proyek->pengeluarans;

        $totalPengeluaran = $pengeluarans->sum('jumlah');

        $sisaAnggaran = max(
            0,
            $proyek->anggaran - $totalPengeluaran
        );

        $persentase = $proyek->anggaran > 0
            ? ($totalPengeluaran / $proyek->anggaran) * 100
            : 0;

        $kategori = [
            'upah_tenaga_kerja' => 'Upah Tenaga Kerja',
            'material' => 'Material',
            'sewa_alat' => 'Sewa Alat/Kendaraan',
            'transportasi' => 'Transportasi',
            'administrasi' => 'Administrasi',
            'lainnya' => 'Pengeluaran Lainnya',
        ];

        return view(
            'admin.pengeluaran-proyek.index',
            compact(
                'proyek',
                'pengeluarans',
                'totalPengeluaran',
                'sisaAnggaran',
                'persentase',
                'kategori'
            )
        );
    }

    public function store(Request $request, $id)
    {
        $this->checkAdmin();

        $proyek = Proyek::findOrFail($id);

        $validated = $request->validate([
            'kategori' => [
                'required',
                'in:upah_tenaga_kerja,material,sewa_alat,transportasi,administrasi,lainnya',
            ],
            'uraian' => 'required|string|max:255',
            'volume' => 'nullable|numeric|min:0',
            'satuan' => 'nullable|string|max:50',
            'harga_satuan' => 'required|numeric|min:0',
            'tanggal_pengeluaran' => 'required|date',
            'keterangan' => 'nullable|string',
        ], [
            'kategori.required' => 'Kategori wajib dipilih.',
            'uraian.required' => 'Uraian pengeluaran wajib diisi.',
            'harga_satuan.required' => 'Harga satuan wajib diisi.',
            'harga_satuan.numeric' => 'Harga satuan harus berupa angka.',
            'tanggal_pengeluaran.required' => 'Tanggal pengeluaran wajib diisi.',
        ]);

        $volume = $validated['volume'] ?? 1;
        $hargaSatuan = $validated['harga_satuan'];

        $jumlah = $volume * $hargaSatuan;

        $totalSaatIni = $proyek->pengeluarans()->sum('jumlah');

        $totalSetelahTambah = $totalSaatIni + $jumlah;

        if ($totalSetelahTambah > $proyek->anggaran) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Pengeluaran tidak dapat disimpan karena melebihi sisa anggaran proyek.'
                );
        }

        PengeluaranProyek::create([
            'id_pembangunan' => $proyek->id_pembangunan,
            'kategori' => $validated['kategori'],
            'uraian' => $validated['uraian'],
            'volume' => $volume,
            'satuan' => $validated['satuan'] ?? null,
            'harga_satuan' => $hargaSatuan,
            'jumlah' => $jumlah,
            'tanggal_pengeluaran' => $validated['tanggal_pengeluaran'],
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        return redirect()
            ->route(
                'admin.proyek.pengeluaran.index',
                $proyek->id_pembangunan
            )
            ->with(
                'success',
                'Pengeluaran proyek berhasil ditambahkan.'
            );
    }

    public function update(
        Request $request,
        $id,
        $pengeluaranId
    ) {
        $this->checkAdmin();

        $proyek = Proyek::findOrFail($id);

        $pengeluaran = PengeluaranProyek::where(
            'id_pembangunan',
            $proyek->id_pembangunan
        )->findOrFail($pengeluaranId);

        $validated = $request->validate([
            'kategori' => [
                'required',
                'in:upah_tenaga_kerja,material,sewa_alat,transportasi,administrasi,lainnya',
            ],
            'uraian' => 'required|string|max:255',
            'volume' => 'nullable|numeric|min:0',
            'satuan' => 'nullable|string|max:50',
            'harga_satuan' => 'required|numeric|min:0',
            'tanggal_pengeluaran' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $volume = $validated['volume'] ?? 1;
        $hargaSatuan = $validated['harga_satuan'];

        $jumlah = $volume * $hargaSatuan;

        $totalPengeluaranLain =
            $proyek->pengeluarans()
                ->where(
                    'id_pengeluaran',
                    '!=',
                    $pengeluaran->id_pengeluaran
                )
                ->sum('jumlah');

        if (($totalPengeluaranLain + $jumlah) > $proyek->anggaran) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Perubahan pengeluaran melebihi sisa anggaran proyek.'
                );
        }

        $pengeluaran->update([
            'kategori' => $validated['kategori'],
            'uraian' => $validated['uraian'],
            'volume' => $volume,
            'satuan' => $validated['satuan'] ?? null,
            'harga_satuan' => $hargaSatuan,
            'jumlah' => $jumlah,
            'tanggal_pengeluaran' => $validated['tanggal_pengeluaran'],
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        return redirect()
            ->route(
                'admin.proyek.pengeluaran.index',
                $proyek->id_pembangunan
            )
            ->with(
                'success',
                'Pengeluaran proyek berhasil diperbarui.'
            );
    }

    public function destroy($id, $pengeluaranId)
    {
        $this->checkAdmin();

        $proyek = Proyek::findOrFail($id);

        $pengeluaran = PengeluaranProyek::where(
            'id_pembangunan',
            $proyek->id_pembangunan
        )->findOrFail($pengeluaranId);

        $pengeluaran->delete();

        return redirect()
            ->route(
                'admin.proyek.pengeluaran.index',
                $proyek->id_pembangunan
            )
            ->with(
                'success',
                'Pengeluaran proyek berhasil dihapus.'
            );
    }
}
