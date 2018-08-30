@extends('layouts.master')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('tests.store') }}">
                {{ csrf_field() }}

                <h5 class="card-title">Test info</h5>

                <div class="form-group">
                    <label for="title">Title</label>
                    <input id="title" type="text" class="form-control" name="title" value="{{ old('title') }}" required placeholder="Title">
                    @if ($errors->has('title'))
                            <span class="form-text text-danger">
                                {{ $errors->first('title') }}
                            </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <input id="description" type="text" class="form-control" name="description" value="{{ old('description') }}" required placeholder="Description">
                    @if ($errors->has('description'))
                            <span class="form-text text-danger">
                                {{ $errors->first('description') }}
                            </span>
                    @endif
                </div>
                
                <div class="form-group">
                    <label for="category">Category</label>
                    <select name="category" class="form-control" id="category">
                        @foreach($category as $cat)
                            <option value="{{$cat->ID_Category}}">{{$cat->Category}}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('category'))
                            <span class="form-text text-danger">
                                {{ $errors->first('category') }}
                            </span>
                    @endif
                </div>
                
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="multiple_submissions" name="multiple_submissions">
                        <label class="form-check-label" for="multiple_submissions">
                            Multiple submissions
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="allocated_time">Allocated time <small>(hours)</small></label>
                    <input id="allocated_time" type="text" class="form-control" name="allocated_time" value="{{ old('allocated_time') }}" required placeholder="Allocated time">
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
