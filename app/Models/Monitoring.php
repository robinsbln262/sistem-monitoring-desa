<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Monitoring extends Model
{
    use HasFactory;

    protected $table = 'monitoring';

    protected $primaryKey = 'id_monitoring';

    protected $fillable = [
        'id_pembangunan',
        'tanggal_monitoring',
        'progres',
        'status_progres',
        'keterangan',
        'komentar_admin',
        'foto_before',
        'foto_after',
        'dana_terpakai',
        'status_verifikasi', // TAMBAHKAN INI
        'catatan_verifikasi', // TAMBAHKAN INI
    ];

    protected $casts = [
        'tanggal_monitoring' => 'date',
        'progres' => 'integer',
        'dana_terpakai' => 'decimal:2',
        'status_verifikasi' => 'string', // TAMBAHKAN INI
    ];

    // ==========================================
    // KONSTANTA STATUS VERIFIKASI
    // ==========================================
    const STATUS_PENDING = 'pending';

    const STATUS_APPROVED = 'approved';

    const STATUS_REJECTED = 'rejected';

    // ==========================================
    // RELASI
    // ==========================================
    public function proyek()
    {
        return $this->belongsTo(Proyek::class, 'id_pembangunan', 'id_pembangunan');
    }

    // ==========================================
    // SCOPE QUERY
    // ==========================================

    /**
     * Scope untuk mendapatkan monitoring yang pending verifikasi
     */
    public function scopePending($query)
    {
        return $query->where('status_verifikasi', self::STATUS_PENDING);
    }

    /**
     * Scope untuk mendapatkan monitoring yang sudah disetujui
     */
    public function scopeApproved($query)
    {
        return $query->where('status_verifikasi', self::STATUS_APPROVED);
    }

    /**
     * Scope untuk mendapatkan monitoring yang ditolak
     */
    public function scopeRejected($query)
    {
        return $query->where('status_verifikasi', self::STATUS_REJECTED);
    }

    /**
     * Scope untuk mendapatkan monitoring yang sudah diverifikasi (approved atau rejected)
     */
    public function scopeVerified($query)
    {
        return $query->whereIn('status_verifikasi', [self::STATUS_APPROVED, self::STATUS_REJECTED]);
    }

    /**
     * Scope untuk mendapatkan monitoring yang tampil di publik (hanya approved)
     */
    public function scopePublished($query)
    {
        return $query->where('status_verifikasi', self::STATUS_APPROVED);
    }

    // ==========================================
    // METHOD VERIFIKASI
    // ==========================================

    /**
     * Setujui monitoring
     */
    public function approve(?string $catatan = null): bool
    {
        return $this->update([
            'status_verifikasi' => self::STATUS_APPROVED,
            'catatan_verifikasi' => $catatan,
        ]);
    }

    /**
     * Tolak monitoring dengan catatan
     */
    public function reject(string $catatan): bool
    {
        return $this->update([
            'status_verifikasi' => self::STATUS_REJECTED,
            'catatan_verifikasi' => $catatan,
        ]);
    }

    // ==========================================
    // METHOD CEK STATUS
    // ==========================================

    /**
     * Cek apakah monitoring masih pending
     */
    public function isPending(): bool
    {
        return $this->status_verifikasi === self::STATUS_PENDING;
    }

    /**
     * Cek apakah monitoring sudah disetujui
     */
    public function isApproved(): bool
    {
        return $this->status_verifikasi === self::STATUS_APPROVED;
    }

    /**
     * Cek apakah monitoring ditolak
     */
    public function isRejected(): bool
    {
        return $this->status_verifikasi === self::STATUS_REJECTED;
    }

    /**
     * Cek apakah monitoring sudah diverifikasi (approved atau rejected)
     */
    public function isVerified(): bool
    {
        return ! $this->isPending();
    }

    /**
     * Cek apakah monitoring bisa diedit (hanya jika pending atau rejected)
     */
    public function isEditable(): bool
    {
        return $this->isPending() || $this->isRejected();
    }

    // ==========================================
    // ACCESSOR
    // ==========================================

    /**
     * Get badge status verifikasi untuk HTML
     */
    public function getStatusVerifikasiBadgeAttribute(): string
    {
        $badges = [
            self::STATUS_PENDING => '<span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i> Menunggu Verifikasi</span>',
            self::STATUS_APPROVED => '<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Disetujui</span>',
            self::STATUS_REJECTED => '<span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i> Ditolak</span>',
        ];

        return $badges[$this->status_verifikasi] ?? $badges[self::STATUS_PENDING];
    }

    /**
     * Get label status verifikasi (plain text)
     */
    public function getStatusVerifikasiLabelAttribute(): string
    {
        $labels = [
            self::STATUS_PENDING => 'Menunggu Verifikasi',
            self::STATUS_APPROVED => 'Disetujui',
            self::STATUS_REJECTED => 'Ditolak',
        ];

        return $labels[$this->status_verifikasi] ?? $labels[self::STATUS_PENDING];
    }

    /**
     * Get class badge status verifikasi
     */
    public function getStatusVerifikasiClassAttribute(): string
    {
        $classes = [
            self::STATUS_PENDING => 'bg-warning text-dark',
            self::STATUS_APPROVED => 'bg-success',
            self::STATUS_REJECTED => 'bg-danger',
        ];

        return $classes[$this->status_verifikasi] ?? $classes[self::STATUS_PENDING];
    }

    /**
     * Get icon status verifikasi
     */
    public function getStatusVerifikasiIconAttribute(): string
    {
        $icons = [
            self::STATUS_PENDING => 'fa-clock',
            self::STATUS_APPROVED => 'fa-check-circle',
            self::STATUS_REJECTED => 'fa-times-circle',
        ];

        return $icons[$this->status_verifikasi] ?? $icons[self::STATUS_PENDING];
    }

    // ==========================================
    // ACCESSOR URL FOTO
    // ==========================================

    /**
     * URL foto Before yang kompatibel dengan:
     * 1. data lama: monitoring_before/nama.jpg
     * 2. data lama: storage/monitoring_before/nama.jpg
     * 3. data baru: uploads/monitoring/before/nama.jpg
     */
    public function getFotoBeforeUrlAttribute(): ?string
    {
        return $this->buildPhotoUrl($this->foto_before);
    }

    /**
     * URL foto After yang kompatibel dengan:
     * 1. data lama: monitoring_after/nama.jpg
     * 2. data lama: storage/monitoring_after/nama.jpg
     * 3. data baru: uploads/monitoring/after/nama.jpg
     */
    public function getFotoAfterUrlAttribute(): ?string
    {
        return $this->buildPhotoUrl($this->foto_after);
    }

    /**
     * Normalisasi path foto agar Blade tidak perlu tahu
     * foto tersebut berasal dari penyimpanan lama atau baru.
     */
    protected function buildPhotoUrl(?string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        $path = trim(str_replace('\\', '/', $path));
        $path = ltrim($path, '/');

        // URL absolut (http/https) tidak diubah.
        if (preg_match('/^https?:\/\//i', $path)) {
            return $path;
        }

        // Jika sudah berupa path public/uploads.
        if (str_starts_with($path, 'uploads/')) {
            return asset($path);
        }

        // Jika nilai database sudah menyimpan "storage/...".
        if (str_starts_with($path, 'storage/')) {
            return asset($path);
        }

        // Format lama dari disk public Laravel.
        // Contoh: monitoring_before/foto.jpg
        return asset('storage/' . $path);
    }

}