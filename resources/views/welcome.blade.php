@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12 mt-4">
            <div class="card border-0 rounded shadow p-4">
                <div class="d-md-flex justify-content-between align-items-center mb-3">
                    <h5>Weather Histories</h5>
                </div>

                <form method="get">
                    <div class="row mb-3">
                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="city">City</label>
                                        <select class="form-control select2" multiple name="city[]">
                                            @foreach (config('custom.weather_history_cities') as $city)
                                                <option value="{{ $city }}" @if(request()->has('city')) {{ in_array($city, request()->city) ? 'selected' : '' }} @else selected @endif>{{ $city }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="date_range">Date Range</label>
                                        <input type="text" class="form-control" name="dates" value="{{ request()->has('dates') ? request()->dates : '' }}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="date_range">Weather Condition</label>
                                        <input type="text" class="form-control" name="weather_condition" value="{{ request()->has('weather_condition') ? request()->weather_condition : '' }}">
                                    </div>
                                </div>
                                <div class="col-md-5 mt-2 d-flex justify-content-between">
                                    <div class="form-group">
                                        <label for="date_range">Min. Temperature</label>
                                        <input type="number" step="0.01" class="form-control" name="min_temperature" value="{{ request()->has('min_temperature') ? request()->min_temperature : '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="date_range">Max. Temperature</label>
                                        <input type="number" step="0.01" class="form-control" name="max_temperature" value="{{ request()->has('max_temperature') ? request()->max_temperature : '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 d-flex align-items-center">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="/" class="btn btn-danger" style="margin-left: 10px">Clear Filter</a>
                        </div>
                    </div>
                </form>
    
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <th>SL#</th>
                            <th>City</th>
                            <th>Coordinates</th>
                            <th>Weather Condition</th>
                            <th>Temperature</th>
                            <th>Temperature Feel Like</th>
                            <th>Humidity</th>
                            <th>Wind Speed</th>
                            <th>Date Time</th>
                            @auth
                                @canany(['edit_weather_record', 'delete_weather_record'])
                                    <th>Action</th>
                                @endcanany
                            @endauth
                        </thead>

                        <tbody>
                            @forelse ($weather_histories as $key => $item)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $item->city }}</td>
                                    <td>{{ $item->lat.', '. $item->lon }}</td>
                                    <td>{{ $item->weather_condition }}</td>
                                    <td>{{ $item->temperature }}&#8451;</td>
                                    <td>{{ $item->temperature_feel_like }}&#8451;</td>
                                    <td>{{ $item->humidity }}</td>
                                    <td>{{ $item->wind_speed }} km/h</td>
                                    <td>{{ formatDate($item->created_at) }}</td>
                                    @auth
                                        @canany(['edit_weather_record', 'delete_weather_record'])
                                            <td>
                                                {!! getDatatableActionButtons($item,
                                                    (checkPermission('edit_weather_record') ? true : false),
                                                    'admin.weather-histories.edit',
                                                    (checkPermission('delete_weather_record') ? true : false),
                                                    'admin.weather-histories.destroy') !!}
                                            </td>
                                        @endcanany
                                    @endauth
                                </tr>
                            @empty
                                <tr colspan="4">
                                    <td>No data available</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center">
                    {{ $weather_histories->links() }}
                </div>
            </div>
        </div><!--end col-->
    </div><!--end row-->
</div>
@endsection

@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice__display{
        padding-left: 18px !important;
    }
</style>
@endpush

@push('js')
@include('shared.sweetalert2.sweetalert2-js')
@include('shared.toastr.toastr-js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
    $(document).ready(function () {
        $(".select2").select2();
        $('input[name="dates"]').daterangepicker({
            maxDate: moment(),
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });

        $('input[name="dates"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' to ' + picker.endDate.format('YYYY-MM-DD'));
        });

        $('input[name="dates"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
    });
</script>
@endpush