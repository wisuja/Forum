@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="page-header">
          <h1>{{ $profileUser->name }}</h1>
          <small>Since {{ $profileUser->created_at->diffForHumans() }}</small>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        @foreach ($threads as $thread)
            <div class="card mt-3">
              <div class="card-header">
                <div class="level">
                  <span class="flex">
                    <a href="{{ $thread->path() }}">{{ $thread->title }}</a>
                  </span>

                  <span>{{ $thread->created_at->diffForHumans() }}</span>
                </div>
              </div>
              <div class="card-body">
                <p>{{ $thread->body }}</p>
              </div>
            </div>
        @endforeach

        {{ $threads->links() }}
      </div>
    </div>
  </div>
@endsection
