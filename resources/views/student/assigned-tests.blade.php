@extends('layouts.master')

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Assigned tests</h5>
            <div class="list-group">
                @if(!empty($studentTests))
                    @foreach($studentTests as $studentTest)
                        @if($studentTest->submitted)
                            <a href="{{ route('complete-assignment', ['id' => $studentTest->Id]) }}" class="list-group-item list-group-item-action custom-list-item alert alert-{{ $studentTest->Status == 'Passed' ? 'success' : ($studentTest->Status == 'Failed' ? 'danger' : 'info') }}">
                                    {{ $studentTest->Test }}
                            </a>
                        @else
                            <a href="{{ route('assignment-warning', ['id' => $studentTest->Id]) }}" class="list-group-item list-group-item-action custom-list-item alert alert-info">
                                    {{ $studentTest->Test }} <span class="badge badge-secondary float-right">New</span>
                            </a>
                        @endif
                    @endforeach
                @else
                    <div class="alert alert-primary" role="alert">
                        You dont have any assignments yet.
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
