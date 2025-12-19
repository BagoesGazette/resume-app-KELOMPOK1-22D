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
        return $this->belongsTo(User::class, 'user_id');
    }

    public function cvSubmission()
    {
        return $this->belongsTo(CvSubmission::class, 'cv_submission_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'draft'     => 'Draft',
            'submitted' => 'Submitted',
            'review'    => 'Review',
            'interview' => 'Interview',
            'accepted'  => 'Diterima',
            'rejected'  => 'Ditolak',
            default     => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'draft'     => '#a0aec0',
            'submitted' => '#667eea',
            'review'    => '#11998e',
            'interview' => '#f7971e',
            'accepted'  => '#38a169',
            'rejected'  => '#e53e3e',
            default     => '#667eea',
        };
    }

    public function getStatusIconAttribute()
    {
        return match ($this->status) {
            'draft'     => 'fas fa-clock',
            'submitted' => 'fas fa-check',
            'review'    => 'fas fa-search',
            'interview' => 'fas fa-calendar',
            'accepted'  => 'fas fa-check',
            'rejected'  => 'fas fa-times',
            default     => 'fas fa-info-circle',
        };
    }


}