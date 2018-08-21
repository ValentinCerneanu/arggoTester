@extends('layouts.master')

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Assignments</h5>
            <a class="btn btn-custom btn-block" href="{{ route('assignments.create') }}">Add Assignment</a>
            <div class="list-group">
                @foreach($assignments as $assignment)
                <div class="d-flex w-100">
                    <a href="{{ route('assignments.show', ['id' => $assignment->Id]) }}" class="list-group-item list-group-item-action custom-list-item alert alert-{{ $assignment->Status == 'Passed' ? 'success' : ($assignment->Status == 'Failed' ? 'danger' : 'info') }}">
                            {{ $assignment->Test . ' > ' . $assignment->Student . ' : ' . $assignment->Student_Email }}
                    </a>
                    <a href="#" class="list-group-item list-group-item-action custom-list-item col-md-2 delete-btn">
                            Delete
                    </a>
                    <form style="display:none!important" action="{{ route('assignments.destroy', ['id' => $assignment->Id]) }}" method="post">
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
            event.preventDefault();         
            if (!$(this).hasClass('disabled')) {   
                $(this).siblings('form').children('button').click();
            }
        });
    </script>
@endsection