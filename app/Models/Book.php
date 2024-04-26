<?php

namespace App\Models;

use App\Models\Category;
use App\Models\Loan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function toArray()
    {
        $array = parent::toArray();

        $array['nama'] = $array['judul'];

        return $array;
    }
}
