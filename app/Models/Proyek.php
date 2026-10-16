<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyek extends Model
{
    use HasFactory;

    protected $table = 'proyek';

    protected $primaryKey = 'id_pembangunan';

    protected $fillable = [
        'id_user',
        'nama_pembangunan',
        'lokasi',
        'tahun_anggaran',
        'tanggal_mulai',
        'tanggal_selesai',
        'sumber_dana',
        'anggaran',

        // DIMENSI PROYEK
        'panjang',
        'lebar',
        'ketebalan_tinggi',

        // HASIL PERHITUNGAN VOLUME
        'volume_proyek',
        'satuan_volume',

        'harga_penawaran',
        'batas_penawaran',
        'status',
        'is_published',
        'rekanan_terpilih',
        'bidang_pekerjaan',
        'status_penawaran',
        'deadline_penawaran',
        'catatan',
    ];

    protected $casts = [
        'tanggal_mulai'       => 'date',
        'tanggal_selesai'     => 'date',

        'anggaran'            => 'decimal:2',

        'panjang'             => 'decimal:2',
        'lebar'               => 'decimal:2',
        'ketebalan_tinggi'    => 'decimal:2',
        'volume_proyek'       => 'decimal:2',

        'harga_penawaran'     => 'decimal:2',

        'batas_penawaran'     => 'date',
        'deadline_penawaran'  => 'date',

        'is_published'        => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'id_user',
            'id_user'
        );
    }

    public function monitorings()
    {
        return $this->hasMany(
            Monitoring::class,
            'id_pembangunan',
            'id_pembangunan'
        );
    }

    public function evaluasis()
    {
        return $this->hasMany(
            Evaluasi::class,
            'id_pembangunan',
            'id_pembangunan'
        );
    }

    public function dokumentasis()
    {
        return $this->hasMany(
            Dokumentasi::class,
            'id_pembangunan',
            'id_pembangunan'
        );
    }

    public function laporans()
    {
        return $this->hasMany(
            Laporan::class,
            'id_pembangunan',
            'id_pembangunan'
        );
    }

    public function rekananProyek()
    {
        return $this->hasMany(
            RekananProyek::class,
            'id_proyek',
            'id_pembangunan'
        );
    }

    public function rekananSetuju()
    {
        return $this->belongsToMany(
            User::class,
            'rekanan_proyek',
            'id_proyek',
            'id_rekanan'
        )
            ->withPivot(
                'status',
                'catatan',
                'harga_penawaran_rekanan',
                'status_penawaran'
            )
            ->wherePivot(
                'status_penawaran',
                'diterima'
            );
    }

    public function komentarMasyarakat()
    {
        return $this->hasMany(
            KomentarMasyarakat::class,
            'id_pembangunan',
            'id_pembangunan'
        );
    }

    public function komentarMasyarakatApproved()
    {
        return $this->hasMany(
            KomentarMasyarakat::class,
            'id_pembangunan',
            'id_pembangunan'
        )
            ->where('status', 'approved')
            ->orderBy('tanggal_komentar', 'desc');
    }

    public function pengeluarans()
    {
        return $this->hasMany(
            PengeluaranProyek::class,
            'id_pembangunan',
            'id_pembangunan'
        );
    }

    // =========================================================
    // EVALUASI REKANAN - RELASI
    // =========================================================

    public function evaluasiRekanan()
    {
        return $this->hasMany(
            EvaluasiRekanan::class,
            'id_pembangunan',
            'id_pembangunan'
        );
    }

    public function evaluasiRekananApproved()
    {
        return $this->hasMany(
            EvaluasiRekanan::class,
            'id_pembangunan',
            'id_pembangunan'
        )->where('status_verifikasi', EvaluasiRekanan::STATUS_APPROVED);
    }

    /**
     * Cek apakah proyek sudah selesai & rekanan sudah isi evaluasi.
     */
    public function getSudahDievaluasiRekananAttribute(): bool
    {
        if (! $this->rekanan_terpilih) {
            return false;
        }

        return $this->evaluasiRekanan()
            ->where('id_rekanan', $this->rekanan_terpilih)
            ->exists();
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /**
     * Volume yang dihitung dari dimensi.
     */
    public function getVolumeHitungAttribute()
    {
        if (
            $this->panjang !== null &&
            $this->lebar !== null &&
            $this->ketebalan_tinggi !== null
        ) {
            return round(
                (float) $this->panjang *
                (float) $this->lebar *
                (float) $this->ketebalan_tinggi,
                2
            );
        }

        return $this->volume_proyek;
    }

    /**
     * Cek apakah proyek mempunyai dimensi lengkap.
     */
    public function getHasDimensiAttribute(): bool
    {
        return
            $this->panjang !== null &&
            $this->lebar !== null &&
            $this->ketebalan_tinggi !== null;
    }

    /**
     * Format dimensi untuk tampilan.
     */
    public function getDimensiFormattedAttribute(): ?string
    {
        if (! $this->has_dimensi) {
            return null;
        }

        return
            rtrim(rtrim(number_format(
                (float) $this->panjang,
                2,
                ',',
                '.'
            ), '0'), ',')
            . ' × ' .
            rtrim(rtrim(number_format(
                (float) $this->lebar,
                2,
                ',',
                '.'
            ), '0'), ',')
            . ' × ' .
            rtrim(rtrim(number_format(
                (float) $this->ketebalan_tinggi,
                2,
                ',',
                '.'
            ), '0'), ',')
            . ' m';
    }

    /**
     * Get progress dari monitoring terbaru.
     */
    public function getProgressAttribute()
    {
        $lastMonitoring = $this->monitorings()
            ->orderBy('tanggal_monitoring', 'desc')
            ->first();

        return $lastMonitoring
            ? (int) $lastMonitoring->progres
            : 0;
    }

    /**
     * Get monitoring terbaru.
     */
    public function getLatestMonitoringAttribute()
    {
        return $this->monitorings()
            ->orderBy('tanggal_monitoring', 'desc')
            ->first();
    }

    /**
     * Get status badge HTML.
     */
    public function getStatusBadgeAttribute()
    {
        $statusMap = [
            'direncanakan' => '<span class="badge bg-secondary rounded-pill px-3 py-2">
                    <i class="fas fa-clock me-1"></i>
                    Direncanakan
                </span>',

            'berjalan' => '<span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                    <i class="fas fa-spinner fa-pulse me-1"></i>
                    Berjalan
                </span>',

            'selesai' => '<span class="badge bg-success rounded-pill px-3 py-2">
                    <i class="fas fa-check-circle me-1"></i>
                    Selesai
                </span>',
        ];

        return $statusMap[$this->status]
            ?? $statusMap['direncanakan'];
    }

    /*
    |--------------------------------------------------------------------------
    | MONITORING & LAPORAN
    |--------------------------------------------------------------------------
    */

    public function getMonitoringApproved()
    {
        return $this->monitorings()
            ->where('status_verifikasi', Monitoring::STATUS_APPROVED)
            ->orderBy('tanggal_monitoring', 'asc');
    }

    public function getMonitoringApprovedCollection()
    {
        return $this->getMonitoringApproved()->get();
    }

    public function getMonitoringPending()
    {
        return $this->monitorings()
            ->where('status_verifikasi', Monitoring::STATUS_PENDING)
            ->orderBy('created_at', 'asc');
    }

    public function getMonitoringPendingCollection()
    {
        return $this->getMonitoringPending()->get();
    }

    public function getTotalDanaTerpakaiAttribute()
    {
        return $this->monitorings()
            ->where('status_verifikasi', Monitoring::STATUS_APPROVED)
            ->whereNotNull('dana_terpakai')
            ->sum('dana_terpakai');
    }

    public function getTotalDanaTerpakaiAllAttribute()
    {
        return $this->monitorings()
            ->whereNotNull('dana_terpakai')
            ->sum('dana_terpakai');
    }

    public function getSisaAnggaranAttribute()
    {
        $totalTerpakai = $this->total_dana_terpakai;

        $anggaran = (float) $this->anggaran;

        return max(0, $anggaran - $totalTerpakai);
    }

    public function getPersentaseAnggaranAttribute()
    {
        $anggaran = (float) $this->anggaran;

        if ($anggaran <= 0) {
            return 0;
        }

        $totalTerpakai = $this->total_dana_terpakai;

        return round(($totalTerpakai / $anggaran) * 100, 2);
    }

    public function getRincianAnggaranAttribute()
    {
        return $this->getMonitoringApproved()
            ->whereNotNull('dana_terpakai')
            ->orderBy('tanggal_monitoring', 'asc')
            ->get()
            ->map(function ($monitoring) {

                return [
                    'tanggal'         => $monitoring->tanggal_monitoring,
                    'progres'         => $monitoring->progres,
                    'status_progres'  => $monitoring->status_progres,
                    'dana_terpakai'   => $monitoring->dana_terpakai,
                    'keterangan'      => $monitoring->keterangan,
                    'persentase_dari_anggaran' => $this->anggaran > 0
                            ? round(
                                ($monitoring->dana_terpakai / $this->anggaran) * 100,
                                2
                            )
                            : 0,
                ];
            });
    }

    public function hasPendingMonitoring(): bool
    {
        return $this->monitorings()
            ->where('status_verifikasi', Monitoring::STATUS_PENDING)
            ->exists();
    }

    public function getPendingMonitoringCountAttribute()
    {
        return $this->monitorings()
            ->where('status_verifikasi', Monitoring::STATUS_PENDING)
            ->count();
    }

    public function getApprovedMonitoringCountAttribute()
    {
        return $this->monitorings()
            ->where('status_verifikasi', Monitoring::STATUS_APPROVED)
            ->count();
    }

    public function getRejectedMonitoringCountAttribute()
    {
        return $this->monitorings()
            ->where('status_verifikasi', Monitoring::STATUS_REJECTED)
            ->count();
    }

    public function getApprovedProgressAttribute()
    {
        $lastApproved = $this->monitorings()
            ->where('status_verifikasi', Monitoring::STATUS_APPROVED)
            ->orderBy('tanggal_monitoring', 'desc')
            ->first();

        return $lastApproved
            ? (int) $lastApproved->progres
            : 0;
    }

    public function getTotalMonitoringCountAttribute()
    {
        return $this->monitorings()->count();
    }

    public function getLatestApprovedMonitoringAttribute()
    {
        return $this->monitorings()
            ->where('status_verifikasi', Monitoring::STATUS_APPROVED)
            ->orderBy('tanggal_monitoring', 'desc')
            ->first();
    }
}