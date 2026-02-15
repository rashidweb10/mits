<!-- resources/views/backend/dashboard.blade.php -->
@extends('backend.layouts.app')

@section('title', 'Dashboard')

@section('content')

@php

    use Illuminate\Support\Facades\Cache;

    $pageCount = \App\Models\Page::when(auth()->user()?->company_id, function ($query, $companyId) {
        return $query->where('company_id', $companyId);
    }, function ($query) {
        //return $query->where('company_id', config('custom.school_id'));
    })->count();
      
    
    // Media count (24 hours cache, unique per user)
    $mediaCount = Cache::remember('media_count_' . (auth()->id() ?? 'guest'), 86400, function () {
        return \App\Models\Upload::when(auth()->user()?->company_id, function ($query, $companyId) {
            return $query->where('user_id', auth()->id());
        })->count();
    });     
    
    // Forms count (24 hours cache)
    $formCount = Cache::remember('forms_count_' . (auth()->user()?->company_id ?? 'all'), 86400, function () {
        return \App\Models\Form::when(auth()->user()?->company_id, function ($query, $companyId) {
            return $query->where('company_id', $companyId);
        })->count();
    });     
    
    $visitors = Cache::remember('visitors_count_' . (auth()->user()?->company_id ?? 'all'), 86400, function () {
        return \App\Models\Visitor::when(auth()->user()?->company_id, function ($query, $companyId) {
            return $query->where('company_id', auth()->user()->company_id);
        })->count();
    }); 
    
    $coursesCount = Cache::remember('courses_count', 86400, function () {
            return \App\Models\Course::count();
    });


    $courseCategoriesCount = Cache::remember('course_category_count', 86400, function () {
            return \App\Models\CourseCategory::count();
    });

    $courseMaterialsCount = Cache::remember('course_material_count', 86400, function () {
            return \App\Models\CourseMaterial::count();
    });

    $courseEnrolmentsCount = Cache::remember('course_enrolment_count', 86400, function () {
            return \App\Models\CourseEnrolment::count();
    });

    $activeStudentsCount = Cache::remember('active_student_count', 86400, function () {
            return \App\Models\User::where('role_id', 3)->where('is_active', 1)->count();
    });

    $inactiveStudentsCount = Cache::remember('inactive_student_count', 86400, function () {
            return \App\Models\User::where('role_id', 3)->where('is_active', 0)->count();
    });    
   
     
@endphp

<div class="page-title-head d-flex align-items-center gap-2">
    <div class="flex-grow-1">
        <h4 class="fs-16 text-uppercase fw-bold mb-0">Dashboard</h4>
    </div>
</div>

<div class="row justify-content-center">
    @include('backend.includes.dashboard-card', [
        'name' => 'Pages',
        'icon' => 'ti ti-pencil',
        'count' => $pageCount,
        'url' => route('pages.index'),
        'bgColor' => '#2098d1',
    ])

    @include('backend.includes.dashboard-card', [
        'name' => 'Media Uploads',
        'icon' => 'ti ti-file-upload',
        'count' => $mediaCount,
        'url' => route('uploaded-files.index'),
        'bgColor' => '#10b981',
    ])

    @include('backend.includes.dashboard-card', [
        'name' => 'Form Submissions',
        'icon' => 'ti ti-forms',
        'count' => $formCount,
        'url' => route('forms.by', ['form_name' => (auth()->user()->company_id == 1) ? 'admission' : 'contact']),
        'bgColor' => '#f59e0b',
    ])

    @include('backend.includes.dashboard-card', [
        'name' => 'Visitors',
        'icon' => 'ti ti-world',
        'count' => $visitors,
        'url' => '',
        'bgColor' => '#8b5cf6',
    ])   

    @include('backend.includes.dashboard-card', [
        'name' => 'courses',
        'icon' => 'ti ti-school',
        'count' => $coursesCount,
        'url' => route('courses.index'),
        'bgColor' => '#ef4444',
    ])   

    @include('backend.includes.dashboard-card', [
        'name' => 'course categories',
        'icon' => 'ti ti-layout-grid',
        'count' => $courseCategoriesCount,
        'url' => route('course-categories.index'),
        'bgColor' => '#06b6d4',
    ])   

    @include('backend.includes.dashboard-card', [
        'name' => 'course materials',
        'icon' => 'ti ti-file-text',
        'count' => $courseMaterialsCount,
        'url' => route('course-materials.index'),
        'bgColor' => '#ec4899',
    ])  

    @include('backend.includes.dashboard-card', [
        'name' => 'course enrolments',
        'icon' => 'ti ti-user-plus',
        'count' => $courseEnrolmentsCount,
        'url' => route('course-enrolments.index'),
        'bgColor' => '#14b8a6',
    ])   

    @include('backend.includes.dashboard-card', [
        'name' => 'active students',
        'icon' => 'ti ti-user-check',
        'count' => $activeStudentsCount,
        'url' => route('students.index', ['status' => 1]),
        'bgColor' => '#22c55e',
    ])

    @include('backend.includes.dashboard-card', [
        'name' => 'inactive students',
        'icon' => 'ti ti-user-x',
        'count' => $inactiveStudentsCount,
        'url' => route('students.index', ['status' => 0]),
        'bgColor' => '#64748b',
    ])
   
</div>
@endsection