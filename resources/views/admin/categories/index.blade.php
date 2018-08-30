@extends('layouts.master')

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Categories </h5>
            <a class="btn btn-custom btn-block" href="{{ route('categories.create') }}">Add Category</a>
            <div class="list-group">
                @foreach($categories as $category)
                    <a href="#" class="list-group-item list-group-item-action custom-list-item">
                       {{ $category->Category }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
