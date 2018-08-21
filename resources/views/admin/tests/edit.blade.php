@extends('layouts.master')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('tests.update', ['id' => $test->Id]) }}">
                {{ csrf_field() }}
                {{ method_field('PUT') }}

                <h5 class="card-title">Test info</h5>

                <div class="form-group">
                    <label for="title">Title</label>
                    <input id="title" type="text" class="form-control" name="title" value="{{ $test->Title }}" required placeholder="Title">
                    @if ($errors->has('title'))
                            <span class="form-text text-danger">
                                {{ $errors->first('title') }}
                            </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <input id="description" type="text" class="form-control" name="description" value="{{ $test->Description }}" required placeholder="Description">
                    @if ($errors->has('description'))
                            <span class="form-text text-danger">
                                {{ $errors->first('description') }}
                            </span>
                    @endif
                </div>
                
                <div class="form-group">
                    <label for="category">Category</label>
                    <select name="category" class="form-control" id="category">
                            <option value="Nav" {{ $test->Category == 'Nav' ? 'selected' : '' }}>Nav</option>
                            <option value="C_x0023_" {{ $test->Category == "C_x0023_" ? 'selected' : '' }}>C#</option>
                            <option value="SQL_Server" {{ $test->Category == "SQL_Server" ? 'selected' : '' }}>SQL Server</option>
                    </select>
                    @if ($errors->has('category'))
                            <span class="form-text text-danger">
                                {{ $errors->first('category') }}
                            </span>
                    @endif
                </div>
                
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" {{ $test->Multiple_Submissions ? 'checked' : '' }} id="multiple_submissions" name="multiple_submissions">
                        <label class="form-check-label" for="multiple_submissions">
                            Multiple submissions
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="allocated_time">Allocated time <small>(hours)</small></label>
                    <input id="allocated_time" type="text" class="form-control" name="allocated_time" value="{{ $test->Allocated_Time }}" required placeholder="Allocated time">
                    @if ($errors->has('allocated_time'))
                            <span class="form-text text-danger">
                                {{ $errors->first('allocated_time') }}
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
