<?php

namespace App\Models;

use App\Models\Kategori;
use App\Models\Peminjaman;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Buku extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class,'id_kategori');
    }

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class);
    }

    public function toArray()
    {
        $array = parent::toArray();

        $array['nama'] = $array['judul'];

        return $array;
    }
}
