@extends('backend.layouts.app')

@section('content')
<div class="page-title-head d-flex align-items-center gap-2">
    <div class="flex-grow-1">
        <h4 class="fs-16 text-uppercase fw-bold mb-0">{{$moduleName}}</h4>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
        <div class="card-header border-bottom border-dashed align-items-center">
            <div class="row w-100">
                <div class="col-md-5">
                    <form class="row g-3 align-items-center">
                        <div class="col-md-8">
                        <select name="company" class="form-select" id="status-select">
                            <option value="" selected>--Select School--</option>
                            @foreach ($companyList as $index => $row)
                                @if(auth()->user()->company_id === null || auth()->user()->company_id == $row->id)
                                    <option value="{{$row->id}}" 
                                        @if(request()->get('company') == $row->id) selected @endif>
                                        {{$row->name}}
                                    </option>
                                @endif
                            @endforeach
                        </select>

                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-success btn-icon w-100">
                                <i class="ti ti-search"></i>
                            </button>
                        </div>
                        <div class="col-md-2">
                            <button type="reset" class="btn btn-warning btn-icon w-100" onclick="window.location.href = '{{ route(Route::currentRouteName()) }}';">
                                <i class="ti ti-refresh"></i>
                            </button>
                        </div>                      
                    </form>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive-sm">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Website</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pageData as $index => $row)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $row->name }}</td>
                                <td><a target="_blank" href="{{ $row->website }}">{{ $row->website }}</a></td>
                                <td>{{ $row->email }}</td>
                                <td>{{ $row->phone }}</td>
                                <td>
                                    <a href="{{ route('companies.edit', $row->id) }}" class="link-reset fs-20 p-1"> <i class="ti ti-pencil"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div> <!-- end table-responsive-->
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div><!-- end row-->
@endsection