@extends('layouts.master')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('answers.store') }}">
                {{ csrf_field() }}

                <h5 class="card-title">Answer info</h5>
                
                <div class="form-group">
                    <label for="question_id">Question</label>
                    <select name="question_id" class="form-control" id="question_id">
                        
                        @foreach($questions as $question)
                            <option value="{{ $question->Id }}" {{ old('question_id') == $question->Id ? 'selected' : '' }}>{{ $question->Text }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('question_id'))
                            <span class="form-text text-danger">
                                {{ $errors->first('question_id') }}
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
                    <label for="validation">Validation</label>
                    <select name="validation" class="form-control" id="validation">
                            <option value="Ok" {{ old('validation') == "Ok" ? 'selected' : '' }}>Right answer</option>
                            <option value="Not_ok" {{ old('validation') == "Not_ok" ? 'selected' : '' }}>Wrong answer</option>
                    </select>
                    @if ($errors->has('validation'))
                            <span class="form-text text-danger">
                                {{ $errors->first('validation') }}
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