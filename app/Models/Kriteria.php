<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    protected $fillable = [
        'code',
        'name',
        'bobot',
    ];

    public function subKriteria()
    {
        return $this->hasMany(SubKriteria::class, 'kriteria_id', 'id');
    }
}
