@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">

                <div class="card-body">
                    <h5 class="card-title">Login with your CP student account to access all our awesome features!</h5>

                    <form method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus placeholder="Email">
                            @if ($errors->has('email'))
                                    <span class="form-text">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="exampleInputPassword1">Password</label>
                            <input id="password" type="password" class="form-control" name="password" placeholder="Password" required>
                            
                            @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-custom">Login</button>
                        
                        <a class="btn btn-link" href="{{ route('password.request') }}">
                            Forgot Your Password?
                        </a>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
