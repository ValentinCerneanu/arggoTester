@extends('layouts.master')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('questions.store') }}">
                {{ csrf_field() }}

                <h5 class="card-title">Question info</h5>
                
                <div class="form-group">
                    <label for="test_id">Test</label>
                    <select name="test_id" class="form-control" id="test_id">
                        
                        @foreach($tests as $test)
                            <option value="{{ $test->Id }}" {{ old('test_id') == $test->Id ? 'selected' : '' }}>{{ $test->Title }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('test_id'))
                            <span class="form-text text-danger">
                                {{ $errors->first('test_id') }}
                            </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="text">Text</label>
                    <input id="text" type="text" class="form-control" name="text" value="{{ old('text') }}" required placeholder="Text">
                    @if ($errors->has('text'))
                            <span class="form-text text-danger">
                                {{ $errors->first('text') }}
                            </span>
                    @endif
                </div>
                
                <div class="form-group">
                    <label for="type">Type</label>
                    <select name="type" class="form-control" id="type">
                            <option value="Single" {{ old('type') == "Single" ? 'selected' : '' }}>Single answer</option>
                            <option value="Text" {{ old('type') == "Text" ? 'selected' : '' }}>Written answer</option>
                    </select>
                    @if ($errors->has('type'))
                            <span class="form-text text-danger">
                                {{ $errors->first('type') }}
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