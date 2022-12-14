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
                            <label for="department">Department</label>
                            <select id="department" name="department" class="form-control" required>
                                <option value="1">Department</option>
                                <option value="2">Department</option>
                            </select>
                        </div>
                        <div class="form-check form-group">
                            <input class="form-check-input" type="checkbox" value="1" name="active" id="active" {{ $employee['active'] ? 'checked' : ''}}>
                            <label class="form-check-label" for="active">
                                Active
                            </label>
                        </div>
                        <a href="{{ route('employee.index') }}"></a>
                        <button type="submit" class="btn btn-secondary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection