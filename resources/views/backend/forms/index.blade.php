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
                    <div class="col-md-8">
                        <form class="row g-3 align-items-center">
                            <div class="col-md-4 d-none">
                                <select name="company" class="form-select" id="status-select">
                                    @foreach ($companyList as $index => $row)
                                        <option value="{{ $row->id }}" 
                                            @if(request()->get('company') == $row->id) selected @endif>
                                            {{ $row->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" value="{{request()->get('search')}}" placeholder="Search">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-success btn-icon w-100">
                                    <i class="ti ti-search"></i>
                                </button>
                            </div>
                            <div class="col-md-2">
                                <button type="reset" class="btn btn-warning btn-icon w-100" 
                                    onclick="window.location.href = '{{ url()->current() }}';">
                                    <i class="ti ti-refresh"></i>
                                </button>
                            </div>
                        </form>                        
                    </div>
                    <div class="col-md-3 offset-md-1 text-end">
                        @if(!auth()->user()->company_id)
                        <div class="btn-group" role="group">
                            @foreach($formNames as $name)
                                <a href="{{ route('forms.by', ['form_name' => $name]) }}"
                                class="btn btn-outline-primary {{ request()->segment(3) == $name ? 'active' : '' }}">
                                    {{ ucfirst($name) }}
                                </a>
                            @endforeach
                        </div>  
                        @endif     
                    </div>
                </div>
                <div class="row mt-3" id="bulkActionsContainer" style="display: none;">
                    <div class="col-md-12">
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted" id="selectedCount">0 items selected</span>
                            <button type="button" class="btn btn-xs btn-danger" onclick="bulkDeleteForms()">
                                <i class="ti ti-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @php
                    $preferredOrder = ['company', 'subject', 'message'];
                    $extraColumns = collect($pageData->items())
                        ->pluck('form_data')
                        ->map(function($data) {
                            // If already an array, just get keys; if JSON string, decode first
                            $arr = is_array($data) ? $data : json_decode($data, true);
                            return is_array($arr) ? array_keys($arr) : [];
                        })
                        ->flatten()
                        ->unique()
                        ->sortBy(function($col) use ($preferredOrder) {
                            return array_search($col, $preferredOrder) !== false ? array_search($col, $preferredOrder) : 999;
                        })                        
                        ->values()
                        ->toArray();
                @endphp              
                <div class="table-responsive-sm table-responsive">
                    <h5>Total: <b>{{ $pageData->total() }}</b></h5>
                    <table class="table table-striped text-truncate">
                        <thead>
                            <tr>
                                <th width="50" class="text-center">
                                    <div class="form-check d-flex justify-content-center">
                                        <input class="form-check-input" type="checkbox" id="selectAll" onchange="toggleSelectAll()" style="cursor: pointer; width: 1.2em; height: 1.2em; margin-top: 0.25em;">
                                    </div>
                                </th>
                                <th class="w-10">#</th>
                                <th class="w-10">Name</th>
                                <th class="w-10">Email</th>
                                <th class="w-10">Phone</th>
                                @foreach($extraColumns as $col)
                                    <th class="w-10">{{ ucfirst(str_replace('_', ' ', $col)) }}</th>
                                @endforeach                                 
                                <th class="w-10">Date</th>
                                <th class="w-10">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pageData as $index => $row)
                            @php
                                $formData = is_array($row->form_data) ? $row->form_data : json_decode($row->form_data, true);
                            @endphp                            
                            <tr>
                                <td class="text-center">
                                    <div class="form-check d-flex justify-content-center">
                                        <input class="form-check-input row-checkbox" type="checkbox" value="{{ $row->id }}" onchange="updateBulkActions()" style="cursor: pointer; width: 1.2em; height: 1.2em; margin-top: 0.25em;">
                                    </div>
                                </td>
                                <td>{{ $index + 1 }}</td>                           
                                <td>{{ $row->name }}</td>                                                           
                                <td>{{ $row->email }}</td>    
                                <td>{{ $row->phone }}</td>
                                @foreach ($extraColumns as $col)
                                    <td>{{ $formData[$col] ?? '-' }}</td>
                                @endforeach                                  

                                <td>{{ formatDatetime($row->updated_at) }}</td>
                                <td>
                                    @if($row->is_registered)

                                        <a onclick="smallModal('{{url(route('course-enrolments.create'))}}?email={{ $row->email }}&category={{ $formData['course_category'] }}', 'Add New')" href="javascript:void(0);" class="link-reset fs-20 p-1"><i class="ti ti-books"></i></a>
                                    
                                    @else

                                        <a href="javascript:void(0);" 
                                        onclick="smallModal('{{ route('students.create', [
                                                'name'  => $row->name,
                                                'email' => $row->email,
                                                'phone' => $row->phone,
                                        ]) }}', 'Add New')" 
                                        class="link-reset fs-20 p-1">
                                        <i class="ti ti-user-plus"></i>
                                        </a>

                                    @endif

                                    <a href="javascript:void(0);" onclick="confirmModal('{{ route('forms.destroy', ['form_name' => request()->segment(3), 'id' => $row->id]) }}', callbackForms )" class="link-reset fs-20 p-1"> <i class="ti ti-trash"></i></a>
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
const callbackForms = function(response) {
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

function bulkDeleteForms() {
    const ids = getSelectedIds();
    if (ids.length === 0) {
        toastr.error('Please select at least one item');
        return;
    }
    
    const message = 'Are you sure you want to delete ' + ids.length + ' selected item(s)?';
    document.getElementById('bulk_delete_message').textContent = message;
    document.getElementById('bulk_delete_ids').value = ids.join(',');
    document.getElementById('bulk_delete_form').setAttribute('action', '{{ route("forms.bulk-delete") }}');
    callBackFunction = callbackBulkForms;
    $('#bulkDeleteModal').modal('show');
}

// Callback function for bulk actions
const callbackBulkForms = function(response) {
    // Close all bulk modals
    $('#bulkDeleteModal').modal('hide');
    
    // Only reload on success
    if (response && response.status) {
        setTimeout(function() {
            location.reload();
        }, 1500);
    }
}
</script>
@endsection
