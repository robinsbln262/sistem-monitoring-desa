<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekananProyek extends Model
{
    use HasFactory;

    protected $table = 'rekanan_proyek';

    protected $primaryKey = 'id_rekanan_proyek';

    protected $fillable = [
        'id_proyek',
        'id_rekanan',
        'status',
        'catatan',
        'harga_penawaran_rekanan',
        'status_penawaran',
        'tanggal_respon',
    ];

    protected $casts = [
        'tanggal_respon' => 'datetime',
        'harga_penawaran_rekanan' => 'decimal:2',
    ];

    public function proyek()
    {
        return $this->belongsTo(Proyek::class, 'id_proyek', 'id_pembangunan');
    }

    public function rekanan()
    {
        return $this->belongsTo(User::class, 'id_rekanan', 'id_user');
    }
}
