@extends('layouts.master')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('categories.store') }}">
                {{ csrf_field() }}

                <h5 class="card-title">Category info</h5>

                <div class="form-group">
                    <label for="name">Name</label>
                    <input id="name" type="test" class="form-control" name="name" value="{{ old('name') }}" required placeholder="Name">
                    @if ($errors->has('name'))
                            <span class="form-test test-danger">
                                {{ $errors->first('name') }}
                            </span>
                    @endif
                </div>
                
                <button type="submit" class="btn btn-custom btn-block">Submit</button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
@endsection