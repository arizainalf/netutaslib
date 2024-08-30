<?php

namespace App\Models;

use App\Models\PeminjamanTahunan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mapel extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function peminjamanTahunans()
    {
        return $this->hasMany(PeminjamanTahunan::class);
    }
}
