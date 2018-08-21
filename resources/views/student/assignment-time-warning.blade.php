@extends('layouts.master')

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $studentTest->Test }} > Notice</h5>
            @if($studentTest->Test_Obj->Multiple_Submissions)
                <div class="alert alert-info" role="alert">
                    <strong>This test allows multiple submissions. You can always return and submit 
                    different answers as long as you are not past the due date!</strong>
                </div>
            @else
                <div class="alert alert-info" role="alert">
                    <strong>This test does not allow multiple submissions. Once you start the test, 
                    submitting will result in your being unable to change your answers!</strong>
                </div>
                @if($studentTest->Test_Obj->Allocated_Time)
                    <div class="alert alert-info" role="alert">
                        <p class="card-text"><strong>This test has a time limit of {{ $studentTest->Test_Obj->Allocated_Time }} 
                        hours. You will be able to return after closing the page provided you have enough time left.</strong></p>
                    </div>
                @else
                    <div class="alert alert-info" role="alert">
                        <p class="card-text"><strong>This test does not have a time limit. Closing the page will 
                        result in your being unable to take the test again without having your answers submitted!</strong></p>
                    </div>
                @endif
            @endif

            <div class="list-group">
                <a href="{{ route('complete-assignment', ['id' => $id]) }}" class="list-group-item list-group-item-action custom-list-item">
                        Start test
                </a>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
