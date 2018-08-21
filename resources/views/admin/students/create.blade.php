@extends('layouts.master')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('students.store') }}">
                {{ csrf_field() }}

                <h5 class="card-title">User info</h5>

                <div class="form-group">
                    <label for="email">Email address</label>
                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus placeholder="Email">
                    @if ($errors->has('email'))
                            <span class="form-text text-danger">
                                {{ $errors->first('email') }}
                            </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input id="password" type="password" class="form-control" name="password" required placeholder="Password">
                    
                    @if ($errors->has('password'))
                            <span class="form-text text-danger">
                                {{ $errors->first('password') }}
                            </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="password-confirm">Confirm Password</label>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required placeholder="Confirm password">
                </div>

                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="admin" name="admin">
                        <label class="form-check-label" for="admin">
                            Is admin
                        </label>
                    </div>
                </div>

                <h5 class="card-title">Student info</h5>
                
                <div class="form-group">
                    <label for="first_name">First name</label>
                    <input id="first_name" type="text" class="form-control" name="first_name" value="{{ old('first_name') }}" required placeholder="First name">
                    @if ($errors->has('first_name'))
                            <span class="form-text text-danger">
                                {{ $errors->first('first_name') }}
                            </span>
                    @endif
                </div>
                
                <div class="form-group">
                    <label for="last_name">Last name</label>
                    <input id="last_name" type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" required placeholder="Last name">
                    @if ($errors->has('last_name'))
                            <span class="form-text text-danger">
                                {{ $errors->first('last_name') }}
                            </span>
                    @endif
                </div>
                
                <div class="form-group">
                    <label for="faculty">Faculty</label>

                    <select name="faculty" class="form-control" id="faculty">
                        @foreach($faculties as $faculty)
                            <option  value="{{ $faculty->Id }}" {{ old('faculty') == $faculty->Id ? 'selected' : '' }}>{{ $faculty->Name }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('faculty'))
                            <span class="form-text text-danger">
                                {{ $errors->first('faculty') }}
                            </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="year_of_study">Year of study</label>
                    <select name="year_of_study" class="form-control" id="year_of_study">
                            <option value="1" {{ old('year_of_study') == 1 ? 'selected' : '' }}>I</option>
                            <option value="2" {{ old('year_of_study') == 2 ? 'selected' : '' }}>II</option>
                            <option value="3" {{ old('year_of_study') == 3 ? 'selected' : '' }}>III</option>
                            <option value="4" {{ old('year_of_study') == 4 ? 'selected' : '' }}>IV</option>
                    </select>
                    @if ($errors->has('year_of_study'))
                            <span class="form-text text-danger">
                                {{ $errors->first('year_of_study') }}
                            </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="enrollment_year">Enrollment Year</label>
                    <input id="enrollment_year" type="text" class="form-control" name="enrollment_year" value="{{ old('enrollment_year') }}" required placeholder="Enrollment Year">
                    @if ($errors->has('enrollment_year'))
                            <span class="form-text text-danger">
                                {{ $errors->first('enrollment_year') }}
                            </span>
                    @endif
                </div>
                
                <div class="form-group">
                    <label for="graduation_year">Graduation year</label>
                    <input id="graduation_year" type="text" class="form-control" name="graduation_year" value="{{ old('graduation_year') }}" required placeholder="Graduation year">
                    @if ($errors->has('graduation_year'))
                            <span class="form-text text-danger">
                                {{ $errors->first('graduation_year') }}
                            </span>
                    @endif
                </div>

                <button type="submit" class="btn btn-custom btn-block">Submit</button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
