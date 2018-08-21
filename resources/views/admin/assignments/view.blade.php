@extends('layouts.master')

@section('content')
    <div class="card">
        <div class="card-body">

        <h5 class="card-title">Assignments</h5>

        <div class="list-group">
            <form method="POST" action="{{ route('assignments.grade', ['id' => $assignment->Id]) }}">
                {{ csrf_field() }}
                <li class="list-group-item">
                    Student: {{ $assignment->Student }}
                </li>
                <li class="list-group-item">
                    Test: {{ $assignment->Test }}
                </li>
                
                @foreach ($answers as $answer)
                    @if (isset($answer->Answer))
                            <li class="list-group-item alert alert-{{ $answer->Validation == 'Ok' ? 'success' : 'danger' }}">
                                <strong>
                                    {{ $answer->Question}}
                                </strong>
                                <br/>
                                {{ $answer->Text }}
                            </li>
                    @endif
                @endforeach

                @foreach ($answers as $answer)
                    @if (!isset($answer->Answer) && isset($answer->Question_Input))
                        <li class="list-group-item alert alert-info">
                            <strong>
                                {{ $answer->Question}}
                            </strong>
                            <br/>
                            {{ $answer->Question_Input }}
                        </li>
                    @endif
                @endforeach

                @foreach ($answers as $answer)
                    @if (!isset($answer->Answer) && !isset($answer->Question_Input))
                        <li class="list-group-item alert alert-warning">
                            <strong>
                                {{ $answer->Question}}
                            </strong>
                            <br/>
                            Not answered
                        </li>
                    @endif
                @endforeach
                
                <li class="list-group-item">
                    Correct single type questions: {{ $assignment->Correct_Single_Type_Questions }}
                </li>

                <li class="list-group-item">
                    <div class="form-group">
                        <label for="grade">Grade:</label>
                    <input class="form-control" type="number" step="0.01" id="grade" name="grade" value="{{ isset($assignment->Grade) ? $assignment->Grade : '0' }}" min="0" />
                    @if ($errors->has('grade'))
                            <span class="form-test test-danger">
                                {{ $errors->first('grade') }}
                            </span>
                        @endif
                    </div>
                </li>

                <li class="list-group-item">
                    <div class="form-group">
                        <label for="status">Status:</label>
                        <select name="status" class="form-control" id="status">
                                <option value="Not_Assessed" {{ $assignment->Status == "Not_Assessed" ? 'selected' : '' }}>Not assessed</option>
                                <option value="Passed" {{ $assignment->Status == "Passed" ? 'selected' : '' }}>Passed</option>
                                <option value="Failed" {{ $assignment->Status == "Failed" ? 'selected' : '' }}>Failed</option>
                        </select>
                        @if ($errors->has('type'))
                                <span class="form-text text-danger">
                                    {{ $errors->first('type') }}
                                </span>
                        @endif
                    </div>
                </li>
                    
                    <button type="submit" class="btn btn-custom btn-block">Grade assignment</button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
@endsection