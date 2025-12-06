<?php

namespace App\Models;

use Carbon\Carbon;
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
        'category'
    ];

    public function applications()
    {
        return $this->hasMany(JobApplication::class, 'jobopening_id');
    }

    public function getTanggalTutupIndoAttribute()
    {
        if (!$this->tanggal_tutup) {
            return null;
        }

        return Carbon::parse($this->tanggal_tutup)
            ->locale('id')
            ->translatedFormat('d F Y');
    }
}