<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobOpening extends Model
{
    protected $table = 'jobopening'; 
    
    protected $fillable = [
        'judul',
        'perusahaan',
        'deskripsi',
        'lokasi',
        'tipe',
        'tanggal_tutup',
        'status',
    ];

    protected $casts = [
        'tanggal_tutup' => 'date',
    ];

    public function applications()
    {
        return $this->hasMany(JobApplication::class, 'jobopening_id');
    }
}