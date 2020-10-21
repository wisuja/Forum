<div class="card mb-3">
  <div class="card-header d-flex flex-column">
    <h5>
      <a href="{{ $thread->path() }}">
        @if (auth()->check() && $thread->hasUpdatesFor(auth()->user()))
          <strong>
            {{ Str::limit($thread->title, 35, '...')  }}
          </strong>
        @else
          {{ Str::limit($thread->title, 35, '...')  }}
        @endif
      </a>
    </h5>
    <div>
      <p class="float-left no-margin">Posted by <a href="{{ route('profile', $thread->creator->name) }}">{{ $thread->creator->name }}</a></p>
      <a href="{{ $thread->path() }}" class="float-right">{{ $thread->replies_count }} {{ Str::plural('reply', $thread->replies_count) }}</a>
    </div>
  </div>
  <div class="card-body">
    {{-- @if (Str::length($thread->body) < 200) --}}
      {!! $thread->body !!}
    {{-- @else
      {{ Str::limit($thread->body, 200, '...') }}
      <br>
      <a href="{{ $thread->path() }}">Read more...</a>
    @endif --}}
  </div>
  <div class="card-footer">
    {{ $thread->visits }} visits.
  </div>
</div>