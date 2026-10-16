<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluasiRekanan extends Model
{
    use HasFactory;

    protected $table = 'evaluasi_rekanan';

    protected $primaryKey = 'id_evaluasi_rekanan';

    protected $fillable = [
        'id_pembangunan',
        'id_rekanan',
        'tanggal_evaluasi',
        'selesai_tepat_waktu',
        'keterlambatan_hari',
        'kendala',
        'hasil_akhir',
        'kualitas_pekerjaan',
        'persentase_penyelesaian',
        'rating',
        'rekomendasi_rekanan',
        'status_verifikasi',
        'catatan_verifikasi',
        'diverifikasi_oleh',
        'tanggal_verifikasi',
    ];

    protected $casts = [
        'tanggal_evaluasi'      => 'date',
        'tanggal_verifikasi'    => 'datetime',
        'selesai_tepat_waktu'   => 'boolean',
        'rating'                => 'integer',
        'persentase_penyelesaian' => 'integer',
        'keterlambatan_hari'    => 'integer',
    ];

    // ==================== KONSTANTA ====================
    const STATUS_PENDING  = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    const KUALITAS_SANGAT_BAIK = 'sangat_baik';
    const KUALITAS_BAIK        = 'baik';
    const KUALITAS_CUKUP       = 'cukup';
    const KUALITAS_KURANG      = 'kurang';

    // ==================== RELASI ====================
    public function proyek()
    {
        return $this->belongsTo(Proyek::class, 'id_pembangunan', 'id_pembangunan');
    }

    public function rekanan()
    {
        return $this->belongsTo(User::class, 'id_rekanan', 'id_user');
    }

    public function verifikator()
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh', 'id_user');
    }

    // ==================== SCOPE ====================
    public function scopePending($query)
    {
        return $query->where('status_verifikasi', self::STATUS_PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status_verifikasi', self::STATUS_APPROVED);
    }

    public function scopeTepatWaktu($query)
    {
        return $query->where('selesai_tepat_waktu', true);
    }

    // ==================== ACCESSOR ====================
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status_verifikasi) {
            self::STATUS_APPROVED => '<span class="badge bg-success rounded-pill px-3 py-2"><i class="fas fa-check-circle me-1"></i>Disetujui</span>',
            self::STATUS_REJECTED => '<span class="badge bg-danger rounded-pill px-3 py-2"><i class="fas fa-times-circle me-1"></i>Ditolak</span>',
            default               => '<span class="badge bg-warning text-dark rounded-pill px-3 py-2"><i class="fas fa-clock me-1"></i>Menunggu Verifikasi</span>',
        };
    }

    public function getKualitasBadgeAttribute(): string
    {
        return match ($this->kualitas_pekerjaan) {
            self::KUALITAS_SANGAT_BAIK => '<span class="badge bg-success rounded-pill px-3 py-2">Sangat Baik</span>',
            self::KUALITAS_BAIK        => '<span class="badge bg-primary rounded-pill px-3 py-2">Baik</span>',
            self::KUALITAS_CUKUP       => '<span class="badge bg-warning text-dark rounded-pill px-3 py-2">Cukup</span>',
            default                    => '<span class="badge bg-danger rounded-pill px-3 py-2">Kurang</span>',
        };
    }

    public function getTepatWaktuBadgeAttribute(): string
    {
        if ($this->selesai_tepat_waktu) {
            return '<span class="badge bg-success rounded-pill px-3 py-2"><i class="fas fa-clock me-1"></i>Tepat Waktu</span>';
        }
        return '<span class="badge bg-danger rounded-pill px-3 py-2"><i class="fas fa-clock me-1"></i>Terlambat ' . $this->keterlambatan_hari . ' hari</span>';
    }

    public function getRatingBintangAttribute(): string
    {
        $full  = str_repeat('*', (int) $this->rating);
        $empty = str_repeat('-', 5 - (int) $this->rating);
        return $full . $empty;
    }
}