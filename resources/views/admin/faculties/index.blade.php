@extends('layouts.master')

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Faculties </h5>
            <a class="btn btn-custom btn-block" href="{{ route('faculties.create') }}">Add Faculty</a>
            <div class="list-group">
                @foreach($faculties as $faculty)
                    <a href="#" class="list-group-item list-group-item-action custom-list-item">
                            {{ $faculty->Name }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
