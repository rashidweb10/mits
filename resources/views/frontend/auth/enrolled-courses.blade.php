@extends('frontend.layouts.profile')

@section('meta.title', 'Enrolled Courses')
@section('meta.description', 'View your enrolled courses')

@php
    $pageTitle = 'Enrolled Courses';
@endphp

@section('profile-content')
<div class="enrolled-courses-container">
    <!-- Page Header -->
    <div class="page-header-section mb-4">
        <h3 class="page-title robot_slab">My Enrolled Courses</h3>
        <p class="page-subtitle">Manage and access all your enrolled courses</p>
    </div>
    
    <!-- Search Filter -->
    <div class="search-section mb-4">
        <form method="GET" action="{{ route('auth.enrolled-courses') }}">
            <div class="row g-2 align-items-center">
                <div class="col-md-7 col-12">
                    <div class="input-group search-input-wrapper">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" 
                            name="search"
                            class="form-control"
                            placeholder="Search by course or category name..."
                            value="{{ request()->get('search') }}">
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-1"></i> Search
                    </button>
                </div>
                @if(request()->get('search'))
                <div class="col-md-2 col-6">
                    <a href="{{ route('auth.enrolled-courses') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-times me-1"></i> Clear
                    </a>
                </div>
                @endif
            </div>
        </form>
    </div>

    @if($enrolledCourses->count() > 0)
        <!-- Table with Horizontal Scroll -->
        <div class="table-scroll-wrapper">
            <div class="table-scroll-container">
                <table class="enrolled-courses-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Category</th>
                            <th>Course Name</th>
                            <th>Enrolled Date</th>
                            <th>Validity</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($enrolledCourses as $index => $enrolment)
                            @php
                                $currentDate = \Carbon\Carbon::now();
                                $validityDate = $enrolment->validity ? \Carbon\Carbon::parse($enrolment->validity) : null;
                                $isExpired = $validityDate && $currentDate->greaterThan($validityDate);
                                $daysRemaining = $validityDate ? $currentDate->diffInDays($validityDate, false) : null;
                            @endphp
                            <tr class="{{ $isExpired ? 'expired' : '' }}">
                                <td class="serial-num">{{ $enrolledCourses->firstItem() + $index }}</td>
                                <td>
                                    <span class="category-badge">
                                        <i class="fas fa-bookmark me-1"></i>
                                        {{ $enrolment->course->category->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <strong class="course-name-text robot_slab">{{ $enrolment->course->name ?? 'N/A' }}</strong>
                                </td>
                                <td>
                                    <div class="date-cell">
                                        <i class="fas fa-calendar-alt me-2"></i>
                                        {{ formatDate($enrolment->created_at) }}
                                    </div>
                                </td>
                                <td>
                                    @if($enrolment->validity)
                                        <div class="date-cell">
                                            <i class="fas fa-calendar-check me-2"></i>
                                            {{ formatDate($enrolment->validity) }}
                                            @if($daysRemaining !== null && $daysRemaining > 0 && !$isExpired)
                                                <span class="days-left">({{ round($daysRemaining) }} days left)</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">
                                            <i class="fas fa-infinity me-2"></i>Lifetime
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($isExpired)
                                        <span class="status-badge status-expired">
                                            <i class="fas fa-exclamation-circle me-1"></i> Expired
                                        </span>
                                    @elseif($daysRemaining !== null && $daysRemaining <= 7)
                                        <span class="status-badge status-warning">
                                            <i class="fas fa-clock me-1"></i> Expiring Soon
                                        </span>
                                    @else
                                        <span class="status-badge status-active">
                                            <i class="fas fa-check-circle me-1"></i> Active
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($isExpired)
                                        <span class="text-danger fw-bold">
                                            <i class="fas fa-lock me-1"></i> Expired
                                        </span>
                                    @else
                                        <div class="action-buttons-group">
                                            <a href="{{ route('auth.enrolled-courses.show', $enrolment->course_id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye me-1"></i> View
                                            </a>
                                            @if($enrolment->course->quiz && $enrolment->course->quiz->is_active)
                                                @php
                                                    $hasPassed = \App\Models\QuizAttempt::where('user_id', auth()->id())
                                                        ->where('quiz_id', $enrolment->course->quiz->id)
                                                        ->where('is_passed', 1)
                                                        ->exists();
                                                @endphp
                                                @if($hasPassed)
                                                    <a href="{{ route('auth.certificate.download', ['certificate' => \App\Models\Certificate::where('user_id', auth()->id())->where('quiz_id', $enrolment->course->quiz->id)->first()->id]) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-certificate me-1"></i> Certificate
                                                    </a>
                                                @else
                                                    <a href="{{ route('auth.quiz-attempt', $enrolment->course->quiz->id) }}" class="btn btn-sm btn-success">
                                                        <i class="fas fa-clipboard-list me-1"></i> Quiz
                                                    </a>
                                                @endif
                                            @endif
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Laravel Pagination -->
        <div class="pagination-section mt-4">
            {{ $enrolledCourses->appends(request()->input())->links() }}
        </div>
    @else
        <div class="empty-state text-center py-5">
            <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
            <p class="text-muted">No courses found.</p>
        </div>
    @endif
</div>

<style>
    .enrolled-courses-container {
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    /* Page Header */
    .page-header-section {
        border-bottom: 2px solid #e9ecef;
        padding-bottom: 15px;
    }

    .page-title {
        color: #2098d1;
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .page-subtitle {
        color: #6c757d;
        font-size: 14px;
        margin: 0;
    }

    /* Search Section */
    .search-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
    }

    .search-input-wrapper .input-group-text {
        background: #fff;
        border-right: none;
        color: #6c757d;
    }

    .search-input-wrapper .form-control {
        border-left: none;
    }

    .search-input-wrapper .form-control:focus {
        box-shadow: none;
        border-color: #2098d1;
    }

    /* Table Scroll Wrapper */
    .table-scroll-wrapper {
        position: relative;
        overflow-x: auto;
        overflow-y: visible;
        -webkit-overflow-scrolling: touch;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .table-scroll-container {
        min-width: 100%;
        width: max-content;
    }

    /* Modern Table */
    .enrolled-courses-table {
        width: 100%;
        min-width: 900px;
        margin-bottom: 0;
        background: #fff;
        border-collapse: separate;
        border-spacing: 0;
    }

    .enrolled-courses-table thead {
        background: linear-gradient(135deg, #2098d1 0%, #0a7ba8 100%);
        color: #fff;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .enrolled-courses-table thead th {
        padding: 15px 20px;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 0.8px;
        border: none;
        white-space: nowrap;
        text-align: left;
    }

    .enrolled-courses-table tbody tr {
        border-bottom: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .enrolled-courses-table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .enrolled-courses-table tbody tr.expired {
        background-color: #fff5f5;
        opacity: 0.8;
    }

    .enrolled-courses-table tbody tr.expired:hover {
        background-color: #ffe5e5;
    }

    .enrolled-courses-table tbody td {
        padding: 18px 20px;
        vertical-align: middle;
        border: none;
    }

    /* Serial Number */
    .serial-num {
        font-weight: 600;
        color: #2098d1;
        font-size: 14px;
        text-align: center;
        width: 50px;
    }

    /* Category Badge */
    .category-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 14px;
        background: #bde3fa70;
        color: #2098d1;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }

    .category-badge i {
        font-size: 10px;
    }

    /* Course Name */
    .course-name-text {
        color: #333;
        font-size: 15px;
        font-weight: 600;
    }

    /* Date Cell */
    .date-cell {
        display: flex;
        align-items: center;
        font-size: 14px;
        color: #555;
    }

    .date-cell i {
        color: #2098d1;
        font-size: 14px;
    }

    .days-left {
        display: block;
        font-size: 12px;
        color: #6c757d;
        margin-top: 4px;
        font-style: italic;
    }

    /* Status Badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }

    .status-active {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: #fff;
    }

    .status-warning {
        background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
        color: #000;
    }

    .status-expired {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: #fff;
    }

    /* Action Buttons */
    .action-buttons-group {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .action-buttons-group .btn {
        font-size: 12px;
        padding: 6px 12px;
        border-radius: 6px;
        white-space: nowrap;
        transition: all 0.3s ease;
    }

    .action-buttons-group .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    /* Pagination */
    .pagination-section {
        display: flex;
        justify-content: center;
    }

    /* Empty State */
    .empty-state {
        padding: 60px 20px;
    }

    /* Custom Scrollbar for Table */
    .table-scroll-wrapper::-webkit-scrollbar {
        height: 8px;
    }

    .table-scroll-wrapper::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    .table-scroll-wrapper::-webkit-scrollbar-thumb {
        background: #2098d1;
        border-radius: 4px;
    }

    .table-scroll-wrapper::-webkit-scrollbar-thumb:hover {
        background: #0a7ba8;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .enrolled-courses-container {
            padding: 20px 15px;
        }

        .page-title {
            font-size: 24px;
        }

        .table-scroll-wrapper {
            margin: 0 -15px;
            padding: 0 15px;
        }

        .action-buttons-group {
            flex-direction: column;
        }

        .action-buttons-group .btn {
            width: 100%;
        }
    }
</style>
@endsection

@section('scripts')
<!-- No DataTable script needed - using Laravel pagination -->
@endsection

