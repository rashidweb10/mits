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
                                <select name="status" class="form-select select2" id="status-select">
                                    <option value="" selected>All Status</option>
                                    <option value="1" @if(request()->get('status') == '1') selected @endif>Active</option>
                                    <option value="0" @if(request()->get('status') == '0') selected @endif>Inactive</option>
                                </select>
                            </div>
                            <div class="col-md">
                                <input type="text" name="search" class="form-control" value="{{request()->get('search')}}" placeholder="Search by name, email, phone, location">
                            </div>
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
                    <div class="col-md-2 text-end">
                        <button onclick="smallModal('{{url(route('students.create'))}}', 'Add New')"
                        class="btn btn-primary btn-icon w-100"><i class="ti ti-plus"></i> Add New</button>        
                    </div>
                </div>
                <div class="row mt-3" id="bulkActionsContainer" style="display: none;">
                    <div class="col-md-12">
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted" id="selectedCount">0 items selected</span>
                            <button type="button" class="btn btn-xs btn-danger" onclick="bulkDeleteStudents()">
                                <i class="ti ti-trash"></i> Delete
                            </button>
                            <button type="button" class="btn btn-xs btn-success" onclick="bulkActiveStudents()">
                                <i class="ti ti-check"></i> Active
                            </button>
                            <button type="button" class="btn btn-xs btn-warning" onclick="bulkInactiveStudents()">
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
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Enrolments</th>
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
                                <td>{{ $row->name ?? 'N/A' }}</td>
                                <td>{{ $row->email ?? 'N/A' }}</td>
                                <td>{{ $row->phone ?? 'N/A' }}</td>
                                <td>{{ $row->location ?? 'N/A' }}</td>
                                <td>
                                <span class="badge {{ $row->is_active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $row->is_active ? 'Active' : 'Inactive' }}
                                </span>                                    
                                </td>
                                <td>
                                    <a target="_blank" href="{{ route('course-enrolments.index', ['search' => $row->email]) }}" class="">
                                        {{ $row->course_enrolments_count ?? 0 }}
                                    </a>
                                </td>
                                <td>{{ formatDatetime($row->created_at) }}</td>
                                <td>{{ formatDatetime($row->updated_at) }}111</td>                                
                                <td>
                                    <a onclick="smallModal('{{url(route('course-enrolments.create'))}}?student_id={{ $row->id }}', 'Add New')" href="javascript:void(0);" class="link-reset fs-20 p-1"><i class="ti ti-books"></i></a>
                                    <a href="javascript:void(0);" onclick="smallModal('{{url(route('students.edit', $row->id))}}', 'Edit')" class="link-reset fs-20 p-1"> <i class="ti ti-pencil"></i></a>
                                    <a href="javascript:void(0);" onclick="confirmModal('{{ route('students.destroy', $row->id) }}', callbackStudents )" class="link-reset fs-20 p-1"> <i class="ti ti-trash"></i></a>
                                    <a href="javascript:void(0);" onclick="loginAsStudent('{{ $row->id }}')" class="link-reset fs-20 p-1"> <i class="ti ti-user-check"></i></a>
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
const callbackStudents = function(response) {
    setTimeout(function() {
        location.reload();
    }, 1500);
}

// Login as student function
function loginAsStudent(studentId) {
    const url = '{{ route("students.login-as", ":id") }}'.replace(':id', studentId);
    $('#loginAsStudentModal').modal('show');
    $('#login_as_student_form').attr('action', url);
}

// Handle login as student form submission
$(document).on('submit', '.ajaxLoginAsStudentForm', function(e) {
    e.preventDefault();
    var form = $(this);
    var action = form.attr('action');
    
    $.ajax({
        type: "GET",
        url: action,
        dataType: 'json',
        success: function(response)
        {
            if (response.status) {
                Command: toastr["success"](response.notification, "Success");
                // Redirect to the specified URL after a delay
                setTimeout(function() {
                    window.location.href = response.redirect;
                }, 1500);
            } else {
                Command: toastr["error"](response.notification, "Alert");
            }
        },
        error: function(xhr) {
            Command: toastr["error"]("An unexpected error occurred. Please try again later.", "Error");
        }
    });
});

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

function bulkDeleteStudents() {
    const ids = getSelectedIds();
    if (ids.length === 0) {
        toastr.error('Please select at least one item');
        return;
    }
    
    const message = 'Are you sure you want to delete ' + ids.length + ' selected item(s)?';
    document.getElementById('bulk_delete_message').textContent = message;
    document.getElementById('bulk_delete_ids').value = ids.join(',');
    document.getElementById('bulk_delete_form').setAttribute('action', '{{ route("students.bulk-delete") }}');
    callBackFunction = callbackBulkStudents;
    $('#bulkDeleteModal').modal('show');
}

function bulkActiveStudents() {
    const ids = getSelectedIds();
    if (ids.length === 0) {
        toastr.error('Please select at least one item');
        return;
    }
    
    const message = 'Are you sure you want to activate ' + ids.length + ' selected item(s)?';
    document.getElementById('bulk_active_message').textContent = message;
    document.getElementById('bulk_active_ids').value = ids.join(',');
    document.getElementById('bulk_active_form').setAttribute('action', '{{ route("students.bulk-active") }}');
    callBackFunction = callbackBulkStudents;
    $('#bulkActiveModal').modal('show');
}

function bulkInactiveStudents() {
    const ids = getSelectedIds();
    if (ids.length === 0) {
        toastr.error('Please select at least one item');
        return;
    }
    
    const message = 'Are you sure you want to deactivate ' + ids.length + ' selected item(s)?';
    document.getElementById('bulk_inactive_message').textContent = message;
    document.getElementById('bulk_inactive_ids').value = ids.join(',');
    document.getElementById('bulk_inactive_form').setAttribute('action', '{{ route("students.bulk-inactive") }}');
    callBackFunction = callbackBulkStudents;
    $('#bulkInactiveModal').modal('show');
}

// Callback function for bulk actions
const callbackBulkStudents = function(response) {
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
</script>
@endsection

