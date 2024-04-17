<?php

namespace App\Models;

use App\Models\Loan;
use App\Models\Visit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
    public function visits()
    {
        return $this->hasMany(Visit::class);
    }
}
