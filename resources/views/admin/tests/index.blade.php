@extends('layouts.master')

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Tests</h5>
            <a class="btn btn-custom btn-block" href="{{ route('tests.create') }}">Add Test</a>
            <div class="list-group">
                @foreach($tests as $test)
                    <div class="d-flex w-100">
                        <a href="{{ route('questions.for-test', ['id' => $test->Id]) }}" class="list-group-item list-group-item-action custom-list-item">
                                {{ $test->Title }}
                        </a>
                        <a href="{{ route('tests.edit', ['id' => $test->Id]) }}" class="list-group-item list-group-item-action custom-list-item col-md-2">
                                Edit
                        </a>
                        <a href="#" class="list-group-item list-group-item-action custom-list-item col-md-2 delete-btn">
                                Delete
                        </a>
                        <form style="display:none!important" action="{{ route('tests.destroy', ['id' => $test->Id]) }}" method="post">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                            <button id='submit-btn' type="submit" class="btn btn-custom submit-btn">Delete</button>
                        </form>
                    </div>
                @endforeach
                
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $('.delete-btn').on('click', function (event) {
            console.log('AAA');
            event.preventDefault();         
            if (!$(this).hasClass('disabled')) {   
                console.log($(this).siblings('form').children('button'));
                $(this).siblings('form').children('button').click();
            }
        });
    </script>
@endsection
