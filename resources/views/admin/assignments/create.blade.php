@extends('layouts.master')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('assignments.store') }}">
                {{ csrf_field() }}

                <h5 class="card-title">Faculty info</h5>
                
                <div class="form-group">
                    <label for="student_id">Student</label>
                    <select name="student_id" class="form-control" id="student_id">
                        
                        @foreach($students as $student)
                            <option value="{{ $student->Id }}" {{ old('student_id') == $student->Id ? 'selected' : '' }}>{{ $student->First_Name . " " . $student->Last_Name }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('student_id'))
                        <span class="form-text text-danger">
                            {{ $errors->first('student_id') }}
                        </span>
                    @endif
                </div>
                
                <div class="form-group">
                    <label for="test_id">Test</label>
                    <select name="test_id" class="form-control" id="test_id">
                        
                        @foreach($tests as $test)
                            <option value="{{ $test->Id }}" {{ old('test_id') == $test->Id ? 'selected' : '' }}>{{ $test->Title }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('test_id'))
                            <span class="form-text text-danger">
                                {{ $errors->first('test_id') }}
                            </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="due_date">Due date</label>
                    <input id="due_date" type="date" class="form-control" name="due_date" value="{{ old('due_date') }}" required placeholder="Due date">
                    @if ($errors->has('due_date'))
                            <span class="form-test test-danger">
                                {{ $errors->first('due_date') }}
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