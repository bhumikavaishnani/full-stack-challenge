{{-- resources/views/admin/companies/form.blade.php --}}
@extends('layouts.admin')

@section('title', isset($company) ? 'Edit Company' : 'Create Company')
@section('page-title', isset($company) ? 'Edit Company' : 'Create Company')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <form method="POST" 
                  action="{{ isset($company) ? route('admin.companies.update', $company) : route('admin.companies.store') }}"
                  x-data="companyForm({{ isset($company) ? json_encode($company->toArray()) : '{}' }})">
                @csrf
                @if(isset($company))
                    @method('PUT')
                @endif
                
                <div class="space-y-6">
                    <!-- Company Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Company Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name"
                               x-model="form.name"
                               required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Description
                        </label>
                        <textarea name="description" 
                                  id="description"
                                  x-model="form.description"
                                  rows="4"
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring"></textarea>

                                  