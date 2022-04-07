@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12 mt-4">
            <div class="card border-0 rounded shadow p-4">
                <div class="d-md-flex justify-content-between align-items-center mb-3">
                    <h5>Role List</h5>
    
                    @can('create_role')
                        <a href="{{ route('admin.roles.create') }}" class="btn btn-info mt-2 text-white"> Create Role</a>
                    @endcan
                </div>
    
                <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                    {!! $dataTable->table(['class'=>'table no-footer dtr-inline'], false) !!}
                </div>
            </div>
        </div><!--end col-->
    </div><!--end row-->
</div>
@endsection

@push('css')
    @include('shared.datatable.datatable-css')
@endpush

@push('js')
    @include('shared.datatable.datatable-js')
    @include('shared.sweetalert2.sweetalert2-js')
    @include('shared.toastr.toastr-js')
@endpush