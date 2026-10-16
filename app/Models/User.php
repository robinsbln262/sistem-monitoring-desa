<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Nama tabel database
     */
    protected $table = 'users';

    /**
     * Primary key
     */
    protected $primaryKey = 'id_user';

    /**
     * Tipe primary key
     */
    protected $keyType = 'int';

    /**
     * Primary key auto increment
     */
    public $incrementing = true;

    /**
     * Kolom yang boleh diisi menggunakan mass assignment
     */
    protected $fillable = [
        'nama_user',
        'username',
        'email',
        'password',
        'level',
        'bidang_keahlian',
        'no_telp',
        'alamat',
        'cv',
    ];

    /**
     * Kolom yang disembunyikan
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | LEVEL USER
    |--------------------------------------------------------------------------
    */

    const LEVEL_ADMIN       = 'admin';
    const LEVEL_REKANAN     = 'rekanan';
    const LEVEL_KEPALA_DESA = 'kepala_desa';

    public function isAdmin(): bool
    {
        return $this->level === self::LEVEL_ADMIN;
    }

    public function isRekanan(): bool
    {
        return $this->level === self::LEVEL_REKANAN;
    }

    public function isKepalaDesa(): bool
    {
        return $this->level === self::LEVEL_KEPALA_DESA;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIP
    |--------------------------------------------------------------------------
    */

    public function proyeks()
    {
        return $this->hasMany(
            Proyek::class,
            'id_user',
            'id_user'
        );
    }

    public function laporans()
    {
        return $this->hasMany(
            Laporan::class,
            'dibuat_oleh',
            'id_user'
        );
    }

    public function rekananProyek()
    {
        return $this->hasMany(
            RekananProyek::class,
            'id_rekanan',
            'id_user'
        );
    }

    public function proyekDisetujui()
    {
        return $this->belongsToMany(
            Proyek::class,
            'rekanan_proyek',
            'id_rekanan',
            'id_proyek'
        )
            ->withPivot([
                'status',
                'catatan',
                'tanggal_respon',
            ])
            ->wherePivot('status', 'setuju');
    }

    // =========================================================
    // EVALUASI REKANAN - RELASI
    // =========================================================

    public function evaluasiRekanan()
    {
        return $this->hasMany(
            EvaluasiRekanan::class,
            'id_rekanan',
            'id_user'
        );
    }

    public function evaluasiRekananApproved()
    {
        return $this->hasMany(
            EvaluasiRekanan::class,
            'id_rekanan',
            'id_user'
        )->where('status_verifikasi', EvaluasiRekanan::STATUS_APPROVED);
    }

    // =========================================================
    // EVALUASI REKANAN - ACCESSOR KREDIBILITAS
    // =========================================================

    /**
     * Rata-rata rating rekanan (1-5).
     */
    public function getRataRatingAttribute(): float
    {
        // Ambil via relasi (hindari $this->evaluasiRekananApproved yang jadi collection)
        $avg = $this->evaluasiRekananApproved()->avg('rating');

        return $avg ? round((float) $avg, 2) : 0;
    }

    /**
     * Total proyek yang sudah dievaluasi.
     */
    public function getTotalProyekDievaluasiAttribute(): int
    {
        return $this->evaluasiRekananApproved()->count();
    }

    /**
     * Total proyek tepat waktu.
     */
    public function getTotalTepatWaktuAttribute(): int
    {
        return $this->evaluasiRekananApproved()
            ->where('selesai_tepat_waktu', true)
            ->count();
    }

    /**
     * Persentase ketepatan waktu.
     */
    public function getPersentaseTepatWaktuAttribute(): float
    {
        $total = $this->total_proyek_dievaluasi;

        if ($total === 0) {
            return 0;
        }

        return round(($this->total_tepat_waktu / $total) * 100, 1);
    }

    /**
     * Skor kredibilitas rekanan (0-100).
     * Kombinasi rating + ketepatan waktu + kualitas.
     *
     * Catatan:
     * - Mengambil data via ->get() supaya konsisten (bukan $this->evaluasiRekananApproved
     *   yang bisa return collection atau relasi tergantung konteks).
     */
    public function getSkorKredibilitasAttribute(): float
    {
        $evaluasis = $this->evaluasiRekananApproved()->get();

        if ($evaluasis->isEmpty()) {
            return 0;
        }

        $total = $evaluasis->count();

        // Rating (40%)
        $avgRating  = (float) $evaluasis->avg('rating'); // 1-5
        $skorRating = ($avgRating / 5) * 40;

        // Ketepatan waktu (30%)
        $tepatWaktu = $evaluasis->where('selesai_tepat_waktu', true)->count();
        $skorWaktu  = ($tepatWaktu / $total) * 30;

        // Kualitas (30%)
        $bobotKualitas = [
            'sangat_baik' => 1.0,
            'baik'        => 0.75,
            'cukup'       => 0.5,
            'kurang'      => 0.25,
        ];

        $totalKualitas = $evaluasis->sum(function ($e) use ($bobotKualitas) {
            return $bobotKualitas[$e->kualitas_pekerjaan] ?? 0.5;
        });

        $skorKualitas = ($totalKualitas / $total) * 30;

        return round($skorRating + $skorWaktu + $skorKualitas, 1);
    }

    /**
     * Label predikat kredibilitas.
     */
    public function getPredikatKredibilitasAttribute(): string
    {
        $skor = $this->skor_kredibilitas;

        if ($skor >= 85) return 'Sangat Baik';
        if ($skor >= 70) return 'Baik';
        if ($skor >= 55) return 'Cukup';

        return 'Perlu Perbaikan';
    }

    /**
     * Badge warna predikat.
     */
    public function getPredikatBadgeAttribute(): string
    {
        $skor = $this->skor_kredibilitas;

        if ($skor >= 85) {
            return '<span class="badge bg-success rounded-pill px-3 py-2">Sangat Baik</span>';
        }
        if ($skor >= 70) {
            return '<span class="badge bg-primary rounded-pill px-3 py-2">Baik</span>';
        }
        if ($skor >= 55) {
            return '<span class="badge bg-warning text-dark rounded-pill px-3 py-2">Cukup</span>';
        }

        return '<span class="badge bg-danger rounded-pill px-3 py-2">Perlu Perbaikan</span>';
    }
}