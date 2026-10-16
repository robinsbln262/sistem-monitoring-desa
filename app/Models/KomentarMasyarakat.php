<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KomentarMasyarakat extends Model
{
    use HasFactory;

    protected $table = 'komentar_masyarakat';

    protected $primaryKey = 'id_komentar';

    protected $fillable = [
        'id_pembangunan',
        'nama',
        'email',
        'komentar',
        'foto', // Tambahkan kolom ini
        'status',
        'tanggal_komentar',
    ];

    protected $casts = [
        'tanggal_komentar' => 'datetime',
    ];

    public function proyek()
    {
        return $this->belongsTo(Proyek::class, 'id_pembangunan', 'id_pembangunan');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
