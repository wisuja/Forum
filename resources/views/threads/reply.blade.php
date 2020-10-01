<div class="card mt-3">
  <div class="card-header">
    <div class="level">
      <h5 class="flex">
        <a href="#">{{ $reply->owner->name }}</a> said
        {{ $reply->created_at->diffForHumans() }}
      </h5>
      <form action="/replies/{{ $reply->id }}/favorites" method="POST">
        @csrf
        <button type="submit" class="btn btn-secondary" {{ $reply->isFavorited() ? 'disabled' : '' }}>
          {{ $reply->favorites_count }} {{ Str::plural('favorite', $reply->favorites_count) }}
        </button>
      </form>
    </div>
  </div>
  <div class="card-body">
      {{ $reply->body }}
  </div>
</div>