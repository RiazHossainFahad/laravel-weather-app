@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12 mt-4">
            <div class="card border-0 rounded shadow p-4">
                <div class="d-md-flex justify-content-between align-items-center mb-3">
                    <h5>Create Role</h5>
                </div>

                <role-form-component 
                    :is_edit="false"
                    :model_data="null"
                    :all_permissions='@json($all_permissions, true)'
                />
    
            </div>
        </div><!--end col-->
    </div><!--end row-->
</div>
@endsection

@push('css')
@endpush

@push('js')
@endpush