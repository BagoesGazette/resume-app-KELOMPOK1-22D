<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CvSubmission extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'cv_file_url',
        'cv_file_name',
        'cv_file_type',
        'cv_file_size',
        'status',
        'ocr_text',
        'processing_error',
        'pendidikan_terakhir',
        'rangkuman_pendidikan',
        'ipk_nilai_akhir',
        'pengalaman_kerja_terakhir',
        'rangkuman_pengalaman_kerja',
        'rangkuman_sertifikasi_prestasi',
        'rangkuman_profil',
        'hardskills',
        'softskills',
        'is_validated',
        'validated_at',
    ];

    protected $casts = [
        'hardskills' => 'array',
        'softskills' => 'array',
        'is_validated' => 'boolean',
        'validated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}