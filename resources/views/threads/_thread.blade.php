<div class="card mb-3">
  <div class="card-header">
    <div class="level">
      <div class="flex">
        <h5>
          <a href="{{ $thread->path() }}">
            @if (auth()->check() && $thread->hasUpdatesFor(auth()->user()))
              <strong>
                {{ $thread->title }}
              </strong>
            @else
              {{ $thread->title }}
            @endif
          </a>
        </h5>
        <h6>Posted by <a href="{{ route('profile', $thread->creator->name) }}">{{ $thread->creator->name }}</a></h6>

      </div>

      <a href="{{ $thread->path() }}">{{ $thread->replies_count }} {{ Str::plural('reply', $thread->replies_count) }}</a>
    </div>
  </div>
  <div class="card-body">
    @if (session('status'))
      <div class="alert alert-success" role="alert">
        {{ session('status') }}
      </div>
    @endif
    {{ $thread->body }}
  </div>
</div>