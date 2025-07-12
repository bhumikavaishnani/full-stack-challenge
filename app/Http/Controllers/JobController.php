<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\JobPosting;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index(Request $request)
    {
        \Log::info("sddfdfdff");
        $query = JobPosting::with('company')
            ->published()
            ->notExpired()
            ->orderBy('published_at', 'desc');

        // Apply filters
        if ($request->filled('position_type')) {
            $query->where('position_type', $request->position_type);
        }

        if ($request->filled('company')) {
            $query->whereHas('company', function ($q) use ($request) {
                $q->where('slug', $request->company);
            });
        }

        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        if ($request->filled('salary_min')) {
            $query->where('salary_min', '>=', $request->salary_min);
        }

        if ($request->filled('salary_max')) {
            $query->where('salary_max', '<=', $request->salary_max);
        }

        if ($request->filled('employment_type')) {
            $query->where('employment_type', $request->employment_type);
        }

        if ($request->filled('experience_level')) {
            $query->where('experience_level', $request->experience_level);
        }

        $jobs = $query->paginate(12);
        $companies = Company::withCount('publishedJobPostings')
            ->having('published_job_postings_count', '>', 0)
            ->orderBy('name')
            ->get();

        if ($request->wantsJson()) {
            return response()->json([
                'jobs' => $jobs,
                'companies' => $companies,
            ]);
        }

        return view('jobs.index', compact('jobs', 'companies'));
    }

    public function show(JobPosting $jobPosting)
    {
        $jobPosting->load('company');
        
        if (!$jobPosting->is_published) {
            abort(404);
        }

        $relatedJobs = JobPosting::with('company')
            ->published()
            ->notExpired()
            ->where('id', '!=', $jobPosting->id)
            ->where('company_id', $jobPosting->company_id)
            ->orWhere('position_type', $jobPosting->position_type)
            ->limit(6)
            ->get();

        return view('jobs.show', compact('jobPosting', 'relatedJobs'));
    }
}
