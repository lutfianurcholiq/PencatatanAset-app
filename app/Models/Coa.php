<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coa extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function jurnals()
    {
        return $this->hasMany(Jurnal::class);
    }
}
