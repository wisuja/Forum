<reply :attributes="{{ $reply }}" inline-template v-cloak>
  <div id="reply_{{ $reply->id }}" class="card mt-3">
    <div class="card-header">
      <div class="level">
        <h5 class="flex">
          <a href="{{ route('profile', $reply->owner) }}">{{ $reply->owner->name }}</a> said
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
      <div v-if="editing">
        <div class="form-group">
          <textarea class="form-control" rows="5" v-model="body"></textarea>
        </div>

        <button class="btn btn-sm btn-primary" @click="update">Update</button>
        <button class="btn btn-sm btn-link" @click="editing = false">Cancel</button>
      </div>
      <div v-else v-text="body"></div>
    </div>
    @can('update', $reply)
      <div class="card-footer">
        <div class="level">
          <button class="btn btn-warning btn-sm mr-3" @click="editing = true">Edit</button>
          <button class="btn btn-danger btn-sm mr-3" @click="destroy">Delete</button>
        </div>
      </div>
    @endcan
  </div>
</reply>
