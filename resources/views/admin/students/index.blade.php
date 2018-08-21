@extends('layouts.master')

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Students</h5>
            <a class="btn btn-custom btn-block" href="{{ route('students.create') }}">Add Student</a>
            <div class="list-group">
                @foreach($students as $student)
                            
                    <div class="d-flex w-100">
                    
                        <a href="{{ route('students.show', ['id' => $student->Id]) }}" class="list-group-item list-group-item-action custom-list-item col-md-8">
                                {{ $student->First_Name . " " . $student->Last_Name . ' > ' . $student->user->Email }}
                        </a>
                        <a href="{{ route('students.edit', ['id' => $student->Id]) }}" class="list-group-item list-group-item-action custom-list-item col-md-2">
                                Edit
                        </a>
                        <a href="#" class="list-group-item list-group-item-action custom-list-item col-md-2 delete-btn {{ $student->User_Id == \Auth::user()->id ? 'disabled' : '' }}">
                                Delete
                        </a>
                        @if($student->User_Id != \Auth::user()->id)
                            <form style="display:none!important" action="{{ route('students.destroy', ['id' => $student->Id]) }}" method="post">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <button id='submit-btn' type="submit" class="btn btn-custom submit-btn">Delete</button>
                            </form>
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