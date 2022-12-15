@extends('layout')

@section('page_title', 'Employees')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="card-header-title">
                            Employees ({{ count($employees) }})
                        </h4>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            @include('partials.messages')
                        </div>
                    </div>
                </div>
            </div>
                @if ( count($employees) )
                    <table class="table table-sm table-nowrap card-table text-center table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Status</th>
                                <th>Last Updated</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employees as $employee)
                                <tr>
                                    <td>
                                        <span>{{ $employee['forename'] . ' ' . $employee['surname'] }}</span>
                                    </td>
                                    <td>
                                        <span>{{ $employee['department']['name'] }}</span>
                                    </td>
                                    <td>
                                        <span>{{ $employee['status'] }}</span>
                                    </td>
                                    <td>
                                        <span>{{ $employee['updated_at']->diffForHumans() }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('employee.edit', $employee['id']) }}" type="button" class="btn btn-secondary">
                                            Edit
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="card-body">
                        <div class="alert alert-light small mb-0">
                            No employees found.
                        </div>
                    </div>
                @endif
            <div class="d-flex justify-content-center">
                {{ $employees->links() }}
            </div>
        </div>
    </div>
</div>

@endsection