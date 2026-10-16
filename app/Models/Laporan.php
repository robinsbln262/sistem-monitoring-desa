<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $table = 'laporan';

    protected $primaryKey = 'id_laporan';

    protected $fillable = [
        'id_pembangunan',
        'jenis_laporan',
        'periode',
        'tanggal_cetak',
        'dibuat_oleh',
    ];

    // Relasi ke Proyek (bukan Pembangunan)
    public function proyek()
    {
        return $this->belongsTo(Proyek::class, 'id_pembangunan', 'id_pembangunan');
    }

    // Alias untuk kompatibilitas (biar ga error di view)
    public function pembangunan()
    {
        return $this->belongsTo(Proyek::class, 'id_pembangunan', 'id_pembangunan');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh', 'id_user');
    }
}
