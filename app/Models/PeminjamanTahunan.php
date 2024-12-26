<?php

namespace App\Models;

use App\Models\Kelas;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PeminjamanTahunan extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class,'id_siswa');
    }
    public function mapel(): BelongsTo
    {
    return $this->belongsTo(Mapel::class,'id_mapel');
    }
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class,'id_kelas');
    }
}
