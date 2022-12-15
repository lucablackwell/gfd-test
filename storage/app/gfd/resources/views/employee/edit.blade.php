@extends('layout')

@section('page_title', $employee->forename . ' ' . $employee->surname)

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-9">
            <div class="card">
               <div class="card-body">
                <h1 class="header-title">
                    {{ $employee->forename . ' ' . $employee->surname }}
                </h1>
                    @include('partials.messages')
                    <form method="post" action="{{ route('employee.update', $employee['id']) }}">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="forename" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="forename" aria-describedby="forenameHelp" name="forename" value="{{ $employee->forename }}"  required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="surname" class="form-label">Surname</label>
                            <input type="text" class="form-control" id="surname" aria-describedby="surnameHelp" name="surname" value="{{ $employee->surname }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="department_id">Department</label>
                            <select id="department_id" name="department_id" class="form-control" required>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ $department->id == $employee->department_id ? 'selected' : '' }}>{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="status">Status</label>
                            <select id="status" name="status" class="form-control" required>
                                <option value="Active" {{ $employee->status == 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="Inactive" {{ $employee->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-secondary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection