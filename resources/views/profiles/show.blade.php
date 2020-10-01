@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="page-header">
          <h1>{{ $profileUser->name }}</h1>
          <small>Joined since {{ $profileUser->created_at->diffForHumans() }}</small>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        @foreach ($activities as $date => $activity)
          <h3 class="page-header">{{ $date }}</h3>
          @foreach ($activity as $record)
            @include("profiles.activities.{$record->type}", ['activity' => $record])
          @endforeach
        @endforeach

        {{-- {{ $threads->links() }} --}}
      </div>
    </div>
  </div>
@endsection
