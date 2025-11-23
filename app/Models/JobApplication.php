<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobApplication extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'jobopening_id', 
        'user_id',
        'cv_submission_id',
        'status',
        'pendidikan_terakhir',
        'rangkuman_pendidikan',
        'ipk_nilai_akhir',
        'pengalaman_kerja_terakhir',
        'rangkuman_pengalaman_kerja',
        'rangkuman_sertifikasi_prestasi',
        'rangkuman_profil',
        'hardskills',
        'softskills',
        'cover_letter',
        'expected_salary',
        'reviewed_by',
        'reviewed_at',
        'review_notes',
    ];

    protected $casts = [
        'hardskills' => 'array',
        'softskills' => 'array',
        'reviewed_at' => 'datetime',
    ];

    public function jobOpening()
    {
        return $this->belongsTo(JobOpening::class, 'jobopening_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cvSubmission()
    {
        return $this->belongsTo(CvSubmission::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}