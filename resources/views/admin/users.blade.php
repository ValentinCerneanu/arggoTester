@extends('layouts.master')

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Users</h5>
            <a class="btn btn-custom btn-block" href="#">Add User</a>
            <div class="list-group">
                @foreach($users as $user)
                    <a href="#" class="list-group-item list-group-item-action custom-list-item">
                            {{ $user->Email }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
