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

                    <form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
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

                        <button type="submit" class="btn btn-custom">
                            Send Password Reset Link
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
