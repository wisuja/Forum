@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="page-header">
          <h1>{{ $profileUser->name }}</h1>
          <small>Joined since {{ $profileUser->created_at->diffForHumans() }}</small>

          @can('update', $profileUser)
            <form method="POST" action="{{ route('avatar', $profileUser) }}" enctype="multipart/form-data">
              @csrf
              <input type="file" name="avatar">
              <div class="form-group">
              <button type="submit" class="btn btn-primary mt-3">Add Avatar</button>
              </div>
            </form>
          @endcan

          <img src="{{ $profileUser->avatar() }}" width="50" height="50">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        @forelse ($activities as $date => $activity)
          <h3 class="page-header">{{ $date }}</h3>
          @foreach ($activity as $record)
            @if (view()->exists("profiles.activities.{$record->type}"))
              @include("profiles.activities.{$record->type}", ['activity' => $record])
            @endif
          @endforeach
        @empty
          <p>There is no activities for this user yet.</p>            
        @endforelse
      </div>
    </div>
  </div>
@endsection
