<?php

namespace App\Http\Controllers;

use App\Models\Evaluasi;
use App\Models\Proyek; // Ganti dari Pembangunan ke Proyek
use Illuminate\Http\Request;

class EvaluasiController extends Controller
{
    /**
     * Display a listing of the resource - Grouped by pembangunan
     */
    /**
     * Display a listing of the resource - Grouped by pembangunan
     * MENGGUNAKAN SERVER-SIDE PAGINATION
     */
    /**
     * Display a listing of the resource - Grouped by pembangunan
     * MENGGUNAKAN SERVER-SIDE PAGINATION
     */
    public function index()
    {
        // =====================================================
        // QUERY PROYEK YANG PUNYA EVALUASI (PAGINATED)
        // =====================================================
        $pembangunans = Proyek::with(['evaluasis' => function ($query) {
                $query->orderBy('tanggal_evaluasi', 'desc');
            }])
            ->whereHas('evaluasis')                     // hanya proyek yang punya evaluasi
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        // =====================================================
        // GROUP DATA
        // =====================================================
        $groupedEvaluasis = [];
        foreach ($pembangunans as $pembangunan) {
            if ($pembangunan->evaluasis->count() > 0) {
                $groupedEvaluasis[] = [
                    'pembangunan'      => $pembangunan,
                    'riwayat'          => $pembangunan->evaluasis,
                    'evaluasi_terbaru' => $pembangunan->evaluasis->first(),
                ];
            }
        }

        // =====================================================
        // UNTUK DROPDOWN DI MODAL TAMBAH
        // =====================================================
        $allPembangunans = Proyek::orderBy('nama_pembangunan')->get();

        return view('evaluasi.index', compact(
            'groupedEvaluasis',
            'allPembangunans',
            'pembangunans'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_pembangunan' => 'required|exists:proyek,id_pembangunan', // Ganti dari pembangunan ke proyek
            'tanggal_evaluasi' => 'required|date',
            'hasil_evaluasi' => 'required',
            'kendala' => 'nullable',
            'rekomendasi' => 'nullable',
        ]);

        Evaluasi::create($request->all());

        return redirect()->route('evaluasi.index')->with('success', 'Data evaluasi berhasil ditambahkan!');
    }

    /**
     * Display the specified resource for AJAX detail.
     */
    public function show($id)
    {
        if (request()->ajax()) {
            $evaluasi = Evaluasi::with('pembangunan')->findOrFail($id);

            return response()->json($evaluasi);
        }

        $evaluasi = Evaluasi::with('pembangunan')->findOrFail($id);

        return view('evaluasi.show', compact('evaluasi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_pembangunan' => 'required|exists:proyek,id_pembangunan', // Ganti dari pembangunan ke proyek
            'tanggal_evaluasi' => 'required|date',
            'hasil_evaluasi' => 'required',
            'kendala' => 'nullable',
            'rekomendasi' => 'nullable',
        ]);

        $evaluasi = Evaluasi::findOrFail($id);
        $evaluasi->update($request->all());

        return redirect()->route('evaluasi.index')->with('success', 'Data evaluasi berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $evaluasi = Evaluasi::findOrFail($id);
        $evaluasi->delete();

        return redirect()->route('evaluasi.index')->with('success', 'Data evaluasi berhasil dihapus!');
    }

    /**
     * Get evaluation data for edit (AJAX)
     */
    public function getData($id)
    {
        try {
            $evaluasi = Evaluasi::findOrFail($id);

            // Format tanggal ke Y-m-d untuk input date
            $evaluasi->tanggal_evaluasi_formatted = date('Y-m-d', strtotime($evaluasi->tanggal_evaluasi));

            return response()->json($evaluasi);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }
    }

    /**
     * Get all evaluations for a specific pembangunan (AJAX)
     */
    public function getByPembangunan($id)
    {
        try {
            $pembangunan = Proyek::findOrFail($id); // Ganti dari Pembangunan ke Proyek
            $evaluasis = Evaluasi::where('id_pembangunan', $id)
                ->orderBy('tanggal_evaluasi', 'desc')
                ->get();

            // Format data untuk ditampilkan
            $formattedEvaluasis = [];
            foreach ($evaluasis as $e) {
                $formattedEvaluasis[] = [
                    'id_evaluasi' => $e->id_evaluasi,
                    'tanggal_evaluasi' => $e->tanggal_evaluasi,
                    'hasil_evaluasi' => $e->hasil_evaluasi,
                    'kendala' => $e->kendala,
                    'rekomendasi' => $e->rekomendasi,
                    'created_at' => $e->created_at,
                ];
            }

            // Tentukan status evaluasi keseluruhan
            $overallStatus = $this->determineOverallStatus($evaluasis);

            return response()->json([
                'success' => true,
                'pembangunan' => [
                    'id_pembangunan' => $pembangunan->id_pembangunan,
                    'nama_pembangunan' => $pembangunan->nama_pembangunan,
                    'lokasi' => $pembangunan->lokasi,
                    'status' => $pembangunan->status,
                    'progress' => $pembangunan->monitorings->first()?->progres ?? 0, // Ambil dari monitoring
                ],
                'evaluasis' => $formattedEvaluasis,
                'overall_status' => $overallStatus,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => 'Data tidak ditemukan: '.$e->getMessage()], 404);
        }
    }

    /**
     * Determine overall status from all evaluations
     */
    private function determineOverallStatus($evaluasis)
    {
        $total = $evaluasis->count();
        if ($total == 0) {
            return 'Belum Dievaluasi';
        }

        $successCount = 0;
        $warningCount = 0;

        foreach ($evaluasis as $e) {
            $hasil = strtolower($e->hasil_evaluasi);
            if (strpos($hasil, 'berhasil') !== false || strpos($hasil, 'sukses') !== false) {
                $successCount++;
            } elseif (strpos($hasil, 'kurang') !== false || strpos($hasil, 'gagal') !== false) {
                $warningCount++;
            }
        }

        if ($successCount == $total) {
            return 'Sangat Baik';
        }
        if ($successCount > $warningCount) {
            return 'Baik';
        }
        if ($warningCount > $successCount) {
            return 'Perlu Perbaikan';
        }

        return 'Cukup';
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
