@extends('layouts.master')

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Answers</h5>
            <a class="btn btn-custom btn-block" href="#">Add Answers</a>
            <div class="list-group">
                @foreach($answers as $answers)
                    <a href="#" class="list-group-item list-group-item-action custom-list-item">
                            {{ $answers->Text }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
