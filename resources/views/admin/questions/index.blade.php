@extends('layouts.master')

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Questions</h5>
            @if ($check && $id != null)
                <a class="btn btn-custom btn-block" href="{{ route('questions.create', ['test_id' => $id]) }}">Add Question</a>
            @else
                <a class="btn btn-custom btn-block" href="{{ route('questions.create') }}">Add Question</a>
            @endif
            <div class="list-group">
                @foreach($questions as $question)
                    <div class="d-flex w-100">
                        <a href="#ans-{{ $question->Id }}" class="list-group-item list-group-item-action custom-list-item col-md-8" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="ans-{{ $question->Id }}">
                                <u><i>{{ $question->Text }}</i></u> @if($question->Type == 'Single') <i class="fas fa-caret-down"></i> @endif
                        </a>
                        <a href="{{ route('questions.edit', ['id' => $question->Id]) }}" class="list-group-item list-group-item-action custom-list-item col-md-2">
                                Edit
                        </a>
                        <a href="#" class="list-group-item list-group-item-action custom-list-item col-md-2 delete-btn">
                                Delete
                        </a>
                        <form style="display:none!important" action="{{ route('questions.destroy', ['id' => $question->Id]) }}" method="post">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <button id='submit-btn' type="submit" class="btn btn-custom submit-btn">Delete</button>
                        </form>
                    </div>
                    <div id='ans-{{ $question->Id }}' class="collapse">
                        @if($question->Type != 'Text')
                            <div class="list-group w-100">
                                <div class="d-flex w-100 custom-dropdown">
                                    <a class="btn btn-custom btn-block" href="{{ route('answers.create', ['question_id' => $question->Id]) }}">Add Answer</a>
                                </div>
                                @foreach($question->answers as $answer)
                                    <div class="d-flex w-100 custom-dropdown">
                                        <a href="#" class="list-group-item list-group-item-action custom-list-item col-md-8">
                                                {{ $answer->Text }}
                                        </a>
                                        <a href="{{ route('answers.edit', ['answer_id' => $answer->Id,' question_id' => $answer->Question_Id]) }}" class="list-group-item list-group-item-action custom-list-item col-md-2">
                                                Edit
                                        </a>
                                        <a href="#" class="list-group-item list-group-item-action custom-list-item col-md-2 delete-btn">
                                                Delete
                                        </a>
                                        <form style="display:none!important" action="{{ route('answers.destroy', ['answer_id' => $answer->Id, 'question_id' => $question->Id]) }}" method="post">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <button id='submit-btn' type="submit" class="btn btn-custom submit-btn">Delete</button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $('.delete-btn').on('click', function (event) {
            event.preventDefault();         
            if (!$(this).hasClass('disabled')) {   
                $(this).siblings('form').children('button').click();
            }
        });
    </script>
@endsection
