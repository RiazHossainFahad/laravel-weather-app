@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12 mt-4">
            <div class="card border-0 rounded shadow p-4">
                <div class="d-md-flex justify-content-between align-items-center mb-3">
                    <h5>Show Role</h5>
                </div>

                <div class="table-responsive">
                    <table
                      class="
                        display
                        table table-hover table-checkable
                        order-column
                        m-t-20
                        width-per-100
                      "
                    >
                      <thead>
                        <tr>
                          <th>Key</th>
                          <th>Value</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Role Name</td>
                          <td>{{ $role->name }}</td>
                        </tr>
                        <tr>
                          <td>Available Permissions</td>
                          <td>
                            <ul class="list-group">
                                @foreach ($role->permissions as $item)
                                    <li class="list-group-item">
                                        {{ \Illuminate\Support\Str::of($item->name)->replace('_', ' ')->ucfirst() }}
                                    </li>
                                @endforeach
                            </ul>
                          </td>
                        </tr>
      
                        <tr>
                          <td></td>
                          <td style="text-align: right">
                            <a href="{{ route('admin.roles.index') }}" class="btn btn-dark mt-2"> Back</a>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                </div>
    
            </div>
        </div><!--end col-->
    </div><!--end row-->
</div>
@endsection

@push('css')
@endpush

@push('js')
@endpush