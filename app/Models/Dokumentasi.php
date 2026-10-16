<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokumentasi extends Model
{
    use HasFactory;

    protected $table = 'dokumentasi';

    protected $primaryKey = 'id_dokumentasi';

    protected $fillable = [
        'id_pembangunan', 'foto', 'keterangan', 'tanggal_upload', 'group_id',
    ];

    protected $casts = [
        'tanggal_upload' => 'date',
    ];

    public function proyek()  // <- Ganti dari pembangunan ke proyek
    {
        return $this->belongsTo(Proyek::class, 'id_pembangunan', 'id_pembangunan');
    }
}
