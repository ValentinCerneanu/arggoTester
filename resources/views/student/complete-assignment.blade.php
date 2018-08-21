@extends('layouts.master')

@section('content')

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $studentTest->Test }}</h5>
            <h6 id='timer'></h6>
            <ul id="tmp" class="list-group">
                <form id="assignment-form" method="POST" action="{{ route('submit-assignment', ['id' => $studentTest->Id]) }}">
                    {{ csrf_field() }}
                    @foreach($questions as $question)
                        @if($question->Type == 'Text')
                            <div class="form-group">
                                <label for="{{ $question->Id }}">{{ $question->Text}}</label>
                                <textarea name="{{ $question->Id }}" class="form-control" id="{{ $question->Id }}" rows="3" {{ $pastDueDate ? 'disabled' : (!$test->Multiple_Submissions && $submitted ? 'disabled' : '') }}>{{ $question->answered->Question_Input }}</textarea>
                            </div>
                        @else
                            <div class="form-group">
                                <label for="{{ $question->Id }}">{{ $question->Text}}</label>
                                <select name="{{ $question->Id }}" class="form-control" id="{{ $question->Id }}" {{ $pastDueDate ? 'disabled' : (!$test->Multiple_Submissions && $submitted ? 'disabled' : '') }}>
                                    @foreach($question->answers as $answer)
                                    <option value="{{ $answer->Id }}" {{ isset($question->answered->Answer) ? ($answer->Id == $question->answered->Answer ? 'selected' : '') : '' }}>{{ $answer->Text }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    @endforeach
                    
                    @if(!$pastDueDate)
                        @if($test->Multiple_Submissions || !$submitted)
                            <button id="submit-button" type="submit" class="btn btn-custom">Submit</button>
                        @else
                            <div class="alert alert-{{ $studentTest->Status == 'Passed' ? 'success' : ($studentTest->Status == 'Failed' ? 'warning' : 'info') }}" role="alert">
                                Submitted! <br/>
                                Grade: {{ isset($studentTest->Grade) ? $studentTest->Grade : '' }} ({{ $studentTest->Status }})
                            </div>
                        @endif
                    @else
                        @if(!$submitted)
                            <div class="alert alert-danger" role="alert">
                                This assignment is past it's due date!
                            </div>
                        @else
                            <div class="alert alert-{{ $studentTest->Status == 'Passed' ? 'success' : ($studentTest->Status == 'Failed' ? 'warning' : 'info') }}" role="alert">
                                Grade: {{ isset($studentTest->Grade) ? $studentTest->Grade : '' }} ({{ $studentTest->Status }})
                            </div>
                        @endif
                    @endif
                </form>
            </ul>
        </div>
    </div>
@endsection

@section('after_scripts')
    <script>    
    var countDownDate = new Date({!! json_encode($studentTest->Started_At) !!}).getTime() + ({!! json_encode($test->Allocated_Time) !!} * 60 * 60 * 1000);

    if ({!! json_encode(!$test->Multiple_Submissions && !$submitted && $test->Allocated_Time > 0) !!}) {
        // Update the count down every 1 second
        var x = setInterval(function() {

        // Get todays date and time
        var now = new Date().getTime();

        // Find the distance between now an the count down date
        var distance = countDownDate - now;

        // Time calculations for days, hours, minutes and seconds
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Output the result in an element with id="demo"
        document.getElementById("timer").innerHTML = days + "d " + hours + "h "
        + minutes + "m " + seconds + "s ";

        // If the count down is over, write some text 
        if (distance < 0) {
            clearInterval(x);
            document.getElementById("timer").innerHTML = "EXPIRED";
            
            $('#submit-button').click();
        }
        }, 1000);
    }

    </script>
@endsection

@section('scripts')
   
@endsection
