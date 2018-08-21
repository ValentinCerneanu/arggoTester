@extends('layouts.master')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('update-password', ['id' => $id]) }}">
                {{ csrf_field() }}

                <h5 class="card-title">Change password</h5>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input id="password" type="password" class="form-control" name="password" required placeholder="Password">
                    
                    @if ($errors->has('password'))
                            <span class="form-text text-danger">
                                {{ $errors->first('password') }}
                            </span>
                    @endif
                </div>
                

                <div class="form-group">
                    <label for="password-confirm">Confirm Password</label>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required placeholder="Confirm password">
                </div>

                <button type="submit" class="btn btn-custom btn-block">Submit</button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
