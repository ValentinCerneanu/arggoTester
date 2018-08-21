@extends('layouts.master')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('universities.store') }}">
                {{ csrf_field() }}

                <h5 class="card-title">University info</h5>

                <div class="form-group">
                    <label for="name">Name</label>
                    <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required placeholder="Name">
                    @if ($errors->has('name'))
                            <span class="form-text text-danger">
                                {{ $errors->first('name') }}
                            </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <input id="address" type="text" class="form-control" name="address" value="{{ old('address') }}" required placeholder="Address">
                    @if ($errors->has('address'))
                            <span class="form-text text-danger">
                                {{ $errors->first('address') }}
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
