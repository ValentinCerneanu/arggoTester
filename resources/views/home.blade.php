@extends('layouts.master')

@section('content')
        <div class="jumbotron">
                <h1 class="display-4">Welcome, {{ $user->name }}</h1>
                <p class="lead">This is a platform developed for the purpose of learning.</p>
                <hr class="my-4">
                <p>This website is powered by Arggo Consulting. For more information please visit our website.</p>
                <a class="btn btn-custom btn-lg" href="http://www.arggo.consulting/" target="_blank" role="button">ARGGO</a>
        </div>
@endsection
