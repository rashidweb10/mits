@extends('backend.layouts.app')

@section('content')
<div class="page-title-head d-flex align-items-center gap-2">
    <div class="flex-grow-1">
        <h4 class="fs-16 text-uppercase fw-bold mb-0">{{$moduleName}}</h4>
    </div>
</div>
@include('backend.includes.alert-message')
@php
    $currentSort = request()->get('sort', 'id');
    $currentDirection = request()->get('direction', 'desc');
@endphp
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
                                    onclick="window.location.href = '{{ route(Route::currentRouteName()) }}';">
                                    <i class="ti ti-refresh"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    <!-- <div class="col-md-2 offset-md-2 text-end">
                        <a href="{{ route($routeName . '.create') }}?layout=default"
                        class="btn btn-primary btn-icon w-100"><i class="ti ti-plus"></i> Add New</a>        
                    </div> -->
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive-sm">
                    <h5>Total: <b>{{ $pageData->total() }}</b></h5>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <!-- <th>Title</th> -->
                                <th>
                                @php
                                    // If currently sorting by title ascending, next click should set desc and vice versa
                                    $titleDirection = ($currentSort === 'title' && $currentDirection === 'asc') ? 'desc' : 'asc';
                                    // Build URL keeping existing query params but reset to page=1
                                    $titleUrl = request()->fullUrlWithQuery(['sort' => 'title', 'direction' => $titleDirection, 'page' => 1]);
                                @endphp

                                <a href="{{ $titleUrl }}" class="text-decoration-none">
                                    Title
                                    @if($currentSort === 'title')
                                    {{-- show active arrow --}}
                                    <span class="ms-1">{{ $currentDirection === 'asc' ? '▲' : '▼' }}</span>
                                    @else
                                    {{-- subtle indicator when not active (optional) --}}
                                    <span class="ms-1 text-muted">⇅</span>
                                    @endif
                                </a>
                                </th>

                                <th>Slug</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pageData as $index => $row)
                            <tr>
                                <td>{{ $index + 1 }}</td>                           
                                <td>{{ $row->title }}</td>                                                           
                                <td><a target="_blank" href="{{ $row->website }}">{{ $row->slug }}</a></td>                                                         
                                <td>
                                <span class="badge {{ $row->is_active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $row->is_active ? 'Active' : 'Inactive' }}
                                </span>                                    
                                </td>
                                <td>{{ formatDatetime($row->created_at) }}</td>
                                <td>{{ formatDatetime($row->updated_at) }}</td>
                                <td>
                                    <a href="{{ route($routeName . '.edit', $row->id) }}?layout={{ $row->layout }}" class="link-reset fs-20 p-1">
                                        <i class="ti ti-pencil"></i>
                                    </a>
                                    <!-- <a href="javascript:void(0);" onclick="confirmModal('{{ route($routeName . '.destroy', $row->id) }}', callback )" class="link-reset fs-20 p-1"> <i class="ti ti-trash"></i></a> -->
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
const callback = function(response) {
    setTimeout(function() {
        location.reload();
    }, 1500);
}
</script>
@endsection