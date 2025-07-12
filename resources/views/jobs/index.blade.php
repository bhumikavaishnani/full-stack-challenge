@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
            Find Your Dream Job
        </h1>
        <p class="text-xl text-gray-600 dark:text-gray-400">
            Discover opportunities that match your skills and passion
        </p>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-8"
         x-data="jobFilters()" x-init="init()">
        
        <!-- Filter Toggle (Mobile) -->
        <div class="md:hidden mb-4">
            <button @click="showFilters = !showFilters" class="flex items-center justify-between w-full px-4 py-2 bg-gray-100 dark:bg-gray-700 rounded-lg">
                <span class="font-medium text-gray-900 dark:text-white">Filters</span>
                <i data-lucide="chevron-down" class="w-5 h-5 text-gray-600 dark:text-gray-400" 
                   x-bind:class="{ 'rotate-180': showFilters }"></i>
            </button>
        </div>

        <!-- Filter Content -->
        <div x-show="showFilters" x-transition class="space-y-4 md:space-y-0 md:grid md:grid-cols-2 lg:grid-cols-4 md:gap-4">
            <!-- Position Type -->
            <div class="form-group">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Position Type</label>
                <select x-model="filters.position_type" @change="applyFilters()" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    <option value="">All Types</option>
                    <option value="remote">Remote</option>
                    <option value="in-person">In-Person</option>
                    <option value="hybrid">Hybrid</option>
                </select>
            </div>

            <!-- Company -->
            <div class="form-group">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Company</label>
                <select x-model="filters.company" @change="applyFilters()" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    <option value="">All Companies</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->slug }}">{{ $company->name }} ({{ $company->published_job_postings_count }})</option>
                    @endforeach
                </select>
            </div>

            <!-- Location -->
            <div class="form-group">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Location</label>
                <input type="text" x-model="filters.location" @input.debounce.300ms="applyFilters()" 
                       placeholder="Enter location" 
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
            </div>

            <!-- Employment Type -->
            <div class="form-group">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Employment Type</label>
                <select x-model="filters.employment_type" @change="applyFilters()" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    <option value="">All Types</option>
                    <option value="full-time">Full-Time</option>
                    <option value="part-time">Part-Time</option>
                    <option value="contract">Contract</option>
                    <option value="internship">Internship</option>
                </select>
            </div>

            <!-- Advanced Filters Toggle -->
            <div class="col-span-full">
                <button @click="showAdvancedFilters = !showAdvancedFilters" 
                        class="flex items-center space-x-2 text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors">
                    <span>Advanced Filters</span>
                    <i data-lucide="chevron-down" class="w-4 h-4" x-bind:class="{ 'rotate-180': showAdvancedFilters }"></i>
                </button>
            </div>

            <!-- Advanced Filters -->
            <div x-show="showAdvancedFilters" x-transition class="col-span-full grid grid-cols-1 md:grid-cols-3 gap-4 pt-4 border-t border-gray-200 dark:border-gray-600">
                <!-- Salary Range -->
                <div class="form-group">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Min Salary</label>
                    <input type="number" x-model="filters.salary_min" @input.debounce.300ms="applyFilters()" 
                           placeholder="e.g., 50000" 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                </div>

                <div class="form-group">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Max Salary</label>
                    <input type="number" x-model="filters.salary_max" @input.debounce.300ms="applyFilters()" 
                           placeholder="e.g., 100000" 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                </div>

                <!-- Experience Level -->
                <div class="form-group">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Experience Level</label>
                    <select x-model="filters.experience_level" @change="applyFilters()" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="">All Levels</option>
                        <option value="entry">Entry Level</option>
                        <option value="mid">Mid Level</option>
                        <option value="senior">Senior Level</option>
                        <option value="lead">Lead Level</option>
                    </select>
                </div>
            </div>

            <!-- Clear Filters -->
            <div class="col-span-full">
                <button @click="clearFilters()" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors">
                    Clear all filters
                </button>
            </div>
        </div>
    </div>

    <!-- Loading State -->
    <div x-show="loading" class="flex justify-center items-center py-12">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
    </div>

    <!-- Results -->
    <div x-show="!loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="jobs-grid">
        @foreach($jobs as $job)
            <div class="job-card bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow duration-200 overflow-hidden">
                <div class="p-6">
                    <!-- Company Logo & Info -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            @if($job->company->logo)
                                <img src="{{ asset('storage/' . $job->company->logo) }}" 
                                     alt="{{ $job->company->name }}" 
                                     class="w-12 h-12 rounded-lg object-cover">
                            @else
                                <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center">
                                    <span class="text-white font-bold text-sm">{{ substr($job->company->name, 0, 1) }}</span>
                                </div>
                            @endif
                            <div>
                                <h3 class="font-semibold text-gray-900 dark:text-white">{{ $job->company->name }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $job->company->city }}</p>
                            </div>
                        </div>
                        
                        <!-- Position Type Badge -->
                        <span class="px-2 py-1 text-xs font-medium rounded-full
                            @if($job->position_type === 'remote') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                            @elseif($job->position_type === 'hybrid') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                            @else bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 @endif">
                            {{ ucfirst($job->position_type) }}
                        </span>
                    </div>

                    <!-- Job Title -->
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ $job->title }}</h2>

                    <!-- Job Details -->
                    <div class="space-y-2 mb-4">
                        @if($job->location)
                            <div class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400">
                                <i data-lucide="map-pin" class="w-4 h-4"></i>
                                <span>{{ $job->location }}</span>
                            </div>
                        @endif
                        
                        <div class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400">
                            <i data-lucide="briefcase" class="w-4 h-4"></i>
                            <span>{{ ucfirst(str_replace('-', ' ', $job->employment_type)) }}</span>
                        </div>
                        
                        <div class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400">
                            <i data-lucide="dollar-sign" class="w-4 h-4"></i>
                            <span>{{ $job->salary_range }}</span>
                        </div>
                    </div>

                    <!-- Skills -->
                    @if($job->skills)
                        <div class="flex flex-wrap gap-1 mb-4">
                            @foreach(array_slice($job->skills, 0, 3) as $skill)
                                <span class="px-2 py-1 text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded">
                                    {{ $skill }}
                                </span>
                            @endforeach
                            @if(count($job->skills) > 3)
                                <span class="px-2 py-1 text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded">
                                    +{{ count($job->skills) - 3 }} more
                                </span>
                            @endif
                        </div>
                    @endif

                    <!-- Description Preview -->
                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-3">
                        {{ Str::limit(strip_tags($job->description), 120) }}
                    </p>

                    <!-- Footer -->
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500 dark:text-gray-500">
                            {{ $job->published_at->diffForHumans() }}
                        </span>
                        <a href="{{ route('jobs.show', $job) }}" 
                           class="inline-flex items-center space-x-2 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors text-sm font-medium">
                            <span>View Details</span>
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- No Results -->
    @if($jobs->isEmpty())
        <div class="text-center py-12">
            <div class="w-24 h-24 mx-auto mb-4 text-gray-400 dark:text-gray-600">
                <i data-lucide="search" class="w-full h-full"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No jobs found</h3>
            <p class="text-gray-600 dark:text-gray-400">Try adjusting your filters or search criteria.</p>
        </div>
    @endif

    <!-- Pagination -->
    @if($jobs->hasPages())
        <div class="mt-8">
            {{ $jobs->withQueryString()->links('pagination::tailwind') }}
        </div>
    @endif
</div>

<script>
function jobFilters() {
    return {
        loading: false,
        showFilters: false,
        showAdvancedFilters: false,
        filters: {
            position_type: '{{ request("position_type") }}',
            company: '{{ request("company") }}',
            location: '{{ request("location") }}',
            employment_type: '{{ request("employment_type") }}',
            salary_min: '{{ request("salary_min") }}',
            salary_max: '{{ request("salary_max") }}',
            experience_level: '{{ request("experience_level") }}'
        },
        
        init() {
            // Show filters on desktop by default
            if (window.innerWidth >= 768) {
                this.showFilters = true;
            }
        },
        
        applyFilters() {
            this.loading = true;
            
            // Build URL with current filters
            const url = new URL(window.location.href);
            
            // Clear existing params
            url.searchParams.delete('position_type');
            url.searchParams.delete('company');
            url.searchParams.delete('location');
            url.searchParams.delete('employment_type');
            url.searchParams.delete('salary_min');
            url.searchParams.delete('salary_max');
            url.searchParams.delete('experience_level');
            
            // Add new params
            Object.keys(this.filters).forEach(key => {
                if (this.filters[key]) {
                    url.searchParams.set(key, this.filters[key]);
                }
            });
            
            // Navigate to new URL
            window.location.href = url.toString();
        },
        
        clearFilters() {
            this.filters = {
                position_type: '',
                company: '',
                location: '',
                employment_type: '',
                salary_min: '',
                salary_max: '',
                experience_level: ''
            };
            
            // Navigate to clean URL
            window.location.href = window.location.pathname;
        }
    };
}
</script>

<style>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.job-card {
    transition: transform 0.2s ease-in-out;
}

.job-card:hover {
    transform: translateY(-2px);
}
</style>
@endsection