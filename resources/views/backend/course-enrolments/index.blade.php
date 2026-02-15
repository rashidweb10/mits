@extends('backend.layouts.app')

@section('content')
<div class="page-title-head d-flex align-items-center gap-2">
    <div class="flex-grow-1">
        <h4 class="fs-16 text-uppercase fw-bold mb-0">{{$moduleName}}</h4>
    </div>
</div>
@include('backend.includes.alert-message')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header border-bottom border-dashed align-items-center">
                <div class="row">
                    <div class="col-md-10">
                        <form class="row g-3 align-items-center">
                            <div class="col-md">
                                <select name="category" class="form-select select2" id="category-select">
                                    <option value="" selected>All Categories</option>
                                    @if(isset($categoryList))
                                        @foreach ($categoryList as $index => $row)
                                            <option value="{{ $row->id }}" 
                                                @if(request()->get('category') == $row->id) selected @endif>
                                                {{ $row->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md">
                                <select name="course" class="form-select select2" id="course-select">
                                    <option value="" selected>All Courses</option>
                                    @if(isset($courseList) && request()->get('category'))
                                        @foreach ($courseList as $index => $row)
                                            <option value="{{ $row->id }}" 
                                                @if(request()->get('course') == $row->id) selected @endif>
                                                {{ $row->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md">
                                <select name="status" class="form-select select2" id="status-select">
                                    <option value="" selected>All Status</option>
                                    <option value="1" @if(request()->get('status') == '1') selected @endif>Active</option>
                                    <option value="0" @if(request()->get('status') == '0') selected @endif>Inactive</option>
                                </select>
                            </div>
                            <div class="col-md">
                                <input type="text" name="search" class="form-control" value="{{request()->get('search')}}" placeholder="Search name, email, phone">
                            </div>
                            <!-- <div class="col-md">
                                <input type="date" name="validity_from" class="form-control" value="{{request()->get('validity_from')}}" placeholder="Validity From">
                            </div>
                            <div class="col-md">
                                <input type="date" name="validity_to" class="form-control" value="{{request()->get('validity_to')}}" placeholder="Validity To">
                            </div> -->
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-success btn-icon w-100">
                                    <i class="ti ti-search"></i>
                                </button>
                            </div>
                            <div class="col-md-1">
                                <button type="reset" class="btn btn-warning btn-icon w-100" 
                                    onclick="window.location.href = '{{ route(Route::currentRouteName()) }}';">
                                    <i class="ti ti-refresh"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-2 text-end d-none">
                        <button onclick="smallModal('{{url(route('course-enrolments.create'))}}', 'Add New')"
                        class="btn btn-primary btn-icon w-100"><i class="ti ti-plus"></i> Add New</button>        
                    </div>
                </div>
                <div class="row mt-3" id="bulkActionsContainer" style="display: none;">
                    <div class="col-md-12">
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted" id="selectedCount">0 items selected</span>
                            <button type="button" class="btn btn-xs btn-danger" onclick="bulkDeleteCourseEnrolments()">
                                <i class="ti ti-trash"></i> Delete
                            </button>
                            <button type="button" class="btn btn-xs btn-success" onclick="bulkActiveCourseEnrolments()">
                                <i class="ti ti-check"></i> Active
                            </button>
                            <button type="button" class="btn btn-xs btn-warning" onclick="bulkInactiveCourseEnrolments()">
                                <i class="ti ti-x"></i> Inactive
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive-sm">
                    <h5>Total: <b>{{ $pageData->total() }}</b></h5>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th width="50" class="text-center">
                                    <div class="form-check d-flex justify-content-center">
                                        <input class="form-check-input" type="checkbox" id="selectAll" onchange="toggleSelectAll()" style="cursor: pointer; width: 1.2em; height: 1.2em; margin-top: 0.25em;">
                                    </div>
                                </th>
                                <th>#</th>
                                {{-- <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th> --}}
                                <th>Course</th>
                                <th>Validity</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Updated At</th>                                
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pageData as $index => $row)
                            <tr>
                                <td class="text-center">
                                    <div class="form-check d-flex justify-content-center">
                                        <input class="form-check-input row-checkbox" type="checkbox" value="{{ $row->id }}" onchange="updateBulkActions()" style="cursor: pointer; width: 1.2em; height: 1.2em; margin-top: 0.25em;">
                                    </div>
                                </td>
                                <td>{{ $pageData->firstItem() + $index }}</td>
                                {{-- <td>{{ $row->user->name ?? 'N/A' }}</td>
                                <td>
                                    @if(!empty($row->user->email))
                                        <a href="{{ route('students.index', ['search' => $row->user->email]) }}"
                                        target="_blank"
                                        class="text-primary">
                                            {{ text_limit($row->user->email) }}
                                        </a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ $row->user->phone ?? 'N/A' }}</td> --}}
                                <td>
                                    @if($row->course)
                                        <a href="{{ url('backend/courses?search=' . urlencode($row->course->name)) }}" 
                                        target="_blank" 
                                        class="text-primary">
                                            {{ text_limit($row->course->name) }}
                                        </a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ $row->validity ? formatDate($row->validity) : 'N/A' }}</td>
                                <td>
                                <span class="badge {{ $row->is_active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $row->is_active ? 'Active' : 'Inactive' }}
                                </span>                                    
                                </td>
                                <td>{{ formatDatetime($row->created_at) }}</td>
                                <td>{{ formatDatetime($row->updated_at) }}</td>                                
                                 <td>
                                     @if($row->certificate)
                                         <a href="{{ route('course-enrolments.certificate', $row->certificate->id) }}" target="_blank" class="link-reset fs-20 p-1"> <i class="ti ti-file-certificate"></i></a>
                                     @endif
                                     <a href="{{ route('course-enrolments.preview', $row->id) }}" target="_blank" class="link-reset fs-20 p-1" title="Preview Course Materials"> <i class="ti ti-eye"></i></a>                                   
                                     <a href="javascript:void(0);" onclick="smallModal('{{url(route('course-enrolments.edit', $row->id))}}', 'Edit')" class="link-reset fs-20 p-1"> <i class="ti ti-pencil"></i></a>
                                     <a href="javascript:void(0);" onclick="confirmModal('{{ route('course-enrolments.destroy', $row->id) }}', callbackCourseEnrolments )" class="link-reset fs-20 p-1"> <i class="ti ti-trash"></i></a>
                                 </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $pageData->appends(request()->input())->links() }}
                </div> <!-- end table-responsive-->
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div><!-- end row-->

<script defer>
const callbackCourseEnrolments = function(response) {
    setTimeout(function() {
        location.reload();
    }, 1500);
}

// Bulk actions functions
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.row-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    updateBulkActions();
}

function updateBulkActions() {
    const checkboxes = document.querySelectorAll('.row-checkbox:checked');
    const selectedCount = checkboxes.length;
    const bulkActionsContainer = document.getElementById('bulkActionsContainer');
    const selectedCountSpan = document.getElementById('selectedCount');
    
    if (selectedCount > 0) {
        bulkActionsContainer.style.display = 'block';
        selectedCountSpan.textContent = selectedCount + ' item(s) selected';
    } else {
        bulkActionsContainer.style.display = 'none';
    }
    
    // Update select all checkbox state
    const allCheckboxes = document.querySelectorAll('.row-checkbox');
    const selectAll = document.getElementById('selectAll');
    if (allCheckboxes.length > 0) {
        selectAll.checked = selectedCount === allCheckboxes.length;
    }
}

function getSelectedIds() {
    const checkboxes = document.querySelectorAll('.row-checkbox:checked');
    const ids = Array.from(checkboxes).map(checkbox => checkbox.value);
    return ids;
}

function bulkDeleteCourseEnrolments() {
    const ids = getSelectedIds();
    if (ids.length === 0) {
        toastr.error('Please select at least one item');
        return;
    }
    
    const message = 'Are you sure you want to delete ' + ids.length + ' selected item(s)?';
    document.getElementById('bulk_delete_message').textContent = message;
    document.getElementById('bulk_delete_ids').value = ids.join(',');
    document.getElementById('bulk_delete_form').setAttribute('action', '{{ route("course-enrolments.bulk-delete") }}');
    callBackFunction = callbackBulkCourseEnrolments;
    $('#bulkDeleteModal').modal('show');
}

function bulkActiveCourseEnrolments() {
    const ids = getSelectedIds();
    if (ids.length === 0) {
        toastr.error('Please select at least one item');
        return;
    }
    
    const message = 'Are you sure you want to activate ' + ids.length + ' selected item(s)?';
    document.getElementById('bulk_active_message').textContent = message;
    document.getElementById('bulk_active_ids').value = ids.join(',');
    document.getElementById('bulk_active_form').setAttribute('action', '{{ route("course-enrolments.bulk-active") }}');
    callBackFunction = callbackBulkCourseEnrolments;
    $('#bulkActiveModal').modal('show');
}

function bulkInactiveCourseEnrolments() {
    const ids = getSelectedIds();
    if (ids.length === 0) {
        toastr.error('Please select at least one item');
        return;
    }
    
    const message = 'Are you sure you want to deactivate ' + ids.length + ' selected item(s)?';
    document.getElementById('bulk_inactive_message').textContent = message;
    document.getElementById('bulk_inactive_ids').value = ids.join(',');
    document.getElementById('bulk_inactive_form').setAttribute('action', '{{ route("course-enrolments.bulk-inactive") }}');
    callBackFunction = callbackBulkCourseEnrolments;
    $('#bulkInactiveModal').modal('show');
}

// Callback function for bulk actions
const callbackBulkCourseEnrolments = function(response) {
    // Close all bulk modals
    $('#bulkDeleteModal').modal('hide');
    $('#bulkActiveModal').modal('hide');
    $('#bulkInactiveModal').modal('hide');
    
    // Only reload on success
    if (response && response.status) {
        setTimeout(function() {
            location.reload();
        }, 1500);
    }
}

// When the category changes, fetch courses for that category via AJAX
$(document).ready(function () {
    $('#category-select').on('change', function () {
        const categoryId = $(this).val();
        const $courseSelect = $('#course-select');

        if (!categoryId) {
            $courseSelect.html('<option value="">All Courses</option>').trigger('change.select2');
            return false;
        }

        // Show a temporary loading option
        $courseSelect.html('<option value="">Loading...</option>');

        $.ajax({
            url: '{{ route('courses.by-category') }}',
            method: 'GET',
            data: {
                category_id: categoryId
            },
            success: function (response) {
                // Reset options
                let options = '<option value="">All Courses</option>';

                if (Array.isArray(response) && response.length) {
                    response.forEach(function (course) {
                        const selected = '{{ request()->get('course') }}' == course.id ? ' selected' : '';
                        options += '<option value="' + course.id + '"' + selected + '>' + course.name + '</option>';
                    });
                }

                $courseSelect.html(options).trigger('change.select2');
            },
            error: function () {
                // On error, just reset to default option
                $courseSelect.html('<option value="">All Courses</option>').trigger('change.select2');
            }
        });
    });

    // Trigger change on page load if category is already selected
    @if(request()->get('category'))
        $('#category-select').trigger('change');
    @endif
});
</script>
@endsection

