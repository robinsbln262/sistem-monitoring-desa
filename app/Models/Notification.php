<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'proyek_id',
        'title',
        'message',
        'url',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    public function proyek()
    {
        return $this->belongsTo(Proyek::class, 'proyek_id', 'id_proyek');
    }
}
