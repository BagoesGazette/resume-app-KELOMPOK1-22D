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
        'interview_date',
        'interview_type',
        'interview_location',
        'interview_notes',
        'accepted_at',
        'start_date',
        'offered_salary',
        'acceptance_notes',
        'rejected_at',
        'rejection_reason',
        'rejection_notes',
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

    public function getInterviewDateFormattedAttribute()
    {
        return $this->interview_date 
            ? $this->interview_date->locale('id')->translatedFormat('l, d F Y - H:i') 
            : null;
    }

     /**
     * Scopes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'submitted');
    }

    public function scopeReviewed($query)
    {
        return $query->where('status', 'reviewed');
    }

    public function scopeInterview($query)
    {
        return $query->where('status', 'interview');
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeUpcomingInterviews($query)
    {
        return $query->where('status', 'interview')
                     ->whereNotNull('interview_date')
                     ->where('interview_date', '>=', now())
                     ->orderBy('interview_date');
    }

}