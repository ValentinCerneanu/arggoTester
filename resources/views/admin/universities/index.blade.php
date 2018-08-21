@extends('layouts.master')

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Universities </h5>
            <a class="btn btn-custom btn-block" href="{{ route('universities.create') }}">Add University</a>
            <div class="list-group">
                <!-- @foreach($universities as $university)
                    <a href="#" class="list-group-item list-group-item-action custom-list-item">
                            {{ $university->Name }}
                    </a>
                @endforeach -->
                @foreach($universities as $university)
                    <div class="d-flex w-100">
                        <a href="#ans-{{ $university->Id }}" class="list-group-item list-group-item-action custom-list-item col-md-8" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="ans-{{ $university->Id }}">
                        {{ $university->Name }} <i class="fas fa-caret-down"></i>
                        </a>
                        <a href="{{ route('universities.edit', ['id' => $university->Id]) }}" class="list-group-item list-group-item-action custom-list-item col-md-2">
                                Edit
                        </a>
                        <a href="#" class="list-group-item list-group-item-action custom-list-item col-md-2 delete-btn">
                                Delete
                        </a>
                        <form style="display:none!important" action="{{ route('universities.destroy', ['id' => $university->Id]) }}" method="post">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                            <button id='submit-btn' type="submit" class="btn btn-custom submit-btn">Delete</button>
                        </form>
                    </div>
                    <div id='ans-{{ $university->Id }}' class="collapse">
                        <div class="list-group w-100">
                        
                    <div class="d-flex w-100 custom-dropdown">
                        <a class="btn btn-custom btn-block" href="{{ route('faculties.create', ['university_id' => $university->Id]) }}">Add Faculty</a>
                    </div>
                            @foreach($university->faculties as $faculty)
                                <div class="d-flex w-100 custom-dropdown">
                                    <a href="#" class="list-group-item list-group-item-action custom-list-item col-md-8">
                                            {{ $faculty->Name }}
                                    </a>
                                    <a href="{{ route('faculties.edit', ['id' => $faculty->Id]) }}" class="list-group-item list-group-item-action custom-list-item col-md-2">
                                            Edit
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action custom-list-item col-md-2 delete-btn">
                                            Delete
                                    </a>
                                    <form style="display:none!important" action="{{ route('faculties.destroy', ['id' => $faculty->Id]) }}" method="post">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <button id='submit-btn' type="submit" class="btn btn-custom submit-btn">Delete</button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
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
