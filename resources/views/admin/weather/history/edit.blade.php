@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12 mt-4">
            <div class="card border-0 rounded shadow p-4">
                <div class="d-md-flex justify-content-between align-items-center mb-3">
                    <h5>Edit Weather Record for City: {{ $weather_history->city }} ({{formatDate($weather_history->created_at)}})</h5>
                </div>

                <weather-history-form-component 
                    :is_edit="true"
                    :model_data='@json($weather_history)'
                    :all_cities='@json(config("custom.weather_history_cities"))'
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