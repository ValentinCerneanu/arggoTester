@extends('layouts.master')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('faculties.update', ['id' => $faculty->Id]) }}">
                {{ csrf_field() }}
                {{ method_field('PUT') }}

                <h5 class="card-title">Faculty info</h5>
                
                <div class="form-group">
                    <label for="university_id">University</label>
                    <select name="university_id" class="form-control" id="university_id">
                        
                        @foreach($universities as $university)
                            <option value="{{ $university->Id }}" {{ $faculty->University_Id == $university->Id ? 'selected' : '' }}>{{ $university->Name }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('university_id'))
                            <span class="form-text text-danger">
                                {{ $errors->first('university_id') }}
                            </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="name">Name</label>
                    <input id="name" type="test" class="form-control" name="name" value="{{ $faculty->Name }}" required placeholder="Name">
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