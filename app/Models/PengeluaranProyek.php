<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengeluaranProyek extends Model
{
    use HasFactory;

    protected $table = 'pengeluaran_proyek';

    protected $primaryKey = 'id_pengeluaran';

    protected $fillable = [
        'id_pembangunan',
        'kategori',
        'uraian',
        'volume',
        'satuan',
        'harga_satuan',
        'jumlah',
        'tanggal_pengeluaran',
        'keterangan',
    ];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'jumlah' => 'decimal:2',
        'tanggal_pengeluaran' => 'date',
    ];

    public function proyek()
    {
        return $this->belongsTo(
            Proyek::class,
            'id_pembangunan',
            'id_pembangunan'
        );
    }

    public function getKategoriLabelAttribute()
    {
        return match ($this->kategori) {
            'upah_tenaga_kerja' => 'Upah Tenaga Kerja',
            'material' => 'Material',
            'sewa_alat' => 'Sewa Alat/Kendaraan',
            'transportasi' => 'Transportasi',
            'administrasi' => 'Administrasi',
            'lainnya' => 'Pengeluaran Lainnya',
            default => ucfirst(
                str_replace('_', ' ', $this->kategori)
            ),
        };
    }
}