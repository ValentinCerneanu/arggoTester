@extends('layouts.master')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">

                <div class="card-body">
                    <h5 class="card-title">Password reset</h5>

                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    
                    <form class="form-horizontal" method="POST" action="{{ route('password.request') }}">
                        {{ csrf_field() }}

                        <input type="hidden" name="token" value="{{ $token }}">
                        
                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}" required autofocus placeholder="Email">
                            @if ($errors->has('email'))
                                    <span class="form-text">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                            @endif
                        </div>
                        
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input id="password" type="password" class="form-control" name="password" required autofocus placeholder="Password">
                            @if ($errors->has('password'))
                                    <span class="form-text">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                            @endif
                        </div>
                        
                        <div class="form-group">
                            <label for="password_confirmation">Password confirmation</label>
                            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required autofocus placeholder="Password confirmation">
                            @if ($errors->has('password_confirmation'))
                                    <span class="form-text">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-custom">
                            Reset password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
