<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluasi extends Model
{
    use HasFactory;

    protected $table = 'evaluasi';

    protected $primaryKey = 'id_evaluasi';

    protected $fillable = [
        'id_pembangunan', 'tanggal_evaluasi', 'hasil_evaluasi', 'kendala', 'rekomendasi',
    ];

    protected $casts = [
        'tanggal_evaluasi' => 'date',
    ];

    public function proyek()  // <- Ganti dari pembangunan ke proyek
    {
        return $this->belongsTo(Proyek::class, 'id_pembangunan', 'id_pembangunan');
    }
}
