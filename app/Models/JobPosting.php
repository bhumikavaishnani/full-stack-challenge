<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class JobPosting extends Model
{
    protected $fillable = [
        'company_id',
        'title',
        'slug',
        'description',
        'requirements',
        'position_type',
        'location',
        'salary_min',
        'salary_max',
        'salary_currency',
        'salary_period',
        'employment_type',
        'skills',
        'experience_level',
        'is_published',
        'published_at',
        'expires_at',
    ];

    protected $casts = [
        'skills' => 'array',
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($jobPosting) {
            if (empty($jobPosting->slug)) {
                $jobPosting->slug = Str::slug($jobPosting->title . '-' . $jobPosting->company->name);
            }
            if ($jobPosting->is_published && empty($jobPosting->published_at)) {
                $jobPosting->published_at = now();
            }
        });

        static::updating(function ($jobPosting) {
            if ($jobPosting->isDirty('title')) {
                $jobPosting->slug = Str::slug($jobPosting->title . '-' . $jobPosting->company->name);
            }
            if ($jobPosting->is_published && $jobPosting->isDirty('is_published') && empty($jobPosting->published_at)) {
                $jobPosting->published_at = now();
            }
        });
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function getSalaryRangeAttribute()
    {
        if ($this->salary_min && $this->salary_max) {
            return number_format($this->salary_min) . ' - ' . number_format($this->salary_max) . ' ' . $this->salary_currency;
        }
        if ($this->salary_min) {
            return 'From ' . number_format($this->salary_min) . ' ' . $this->salary_currency;
        }
        if ($this->salary_max) {
            return 'Up to ' . number_format($this->salary_max) . ' ' . $this->salary_currency;
        }
        return 'Salary not specified';
    }
}
