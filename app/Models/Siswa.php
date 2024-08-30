<?php

namespace App\Models;

use App\Models\Visit;
use App\Models\Kunjungan;
use App\Models\Peminjaman;
use App\Models\PeminjamanTahunan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Siswa extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function peminjamans()
    {
        return $this->HasMany(Peminjaman::class);
    }
    public function peminjamanTahunans()
    {
        return $this->hasMany(PeminjamanTahunan::class);
    }
    public function kunjungans()
    {
        return $this->hasMany(Kunjungan::class);
    }    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class,'id_kelas');
    }
}
