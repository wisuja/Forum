<div class="card mt-3" v-if="editing">
  <form @submit.prevent="update">
    <div class="card-header d-flex flex-row">
      <h5 class="flex-grow-1 no-margin align-self-center w-75">
          <div class="form-group">
            <input type="text" class="form-control" v-model="form.title" required>
          </div>
      </h5>
  </div>

  <div class="card-body">
      <textarea rows="10" class="form-control" v-model="form.body" required></textarea>
  </div>

  @can('update', $thread)
    <div class="card-footer">
        <div class="d-flex">
          <button type="submit" class="btn btn-warning mr-1">Update</button>
          <button type="button" class="btn btn-default mr-auto" @click="cancel ">Cancel</button>
  </form>
          <form action="{{ $thread->path() }}" method="POST">
              @method('DELETE')
              @csrf
              <button type="submit" class="btn btn-danger">Delete</button>
          </form>
        </div>
      </div>
    @endcan
</div>

<div class="card mt-3" v-else>
  <div class="card-header d-flex flex-row">
      <h5 class="flex-grow-1 no-margin align-self-center w-75">
          <img src="{{ $thread->creator->avatar_path }}" alt="" width="25" height="25">
          <a href="{{ route('profile',$thread->creator) }}">{{ $thread->creator->name }}</a> posted:
          <span v-text="form.title"></span>
      </h5>
  </div>

  <div class="card-body">
    <span v-text="form.body"></span>
  </div>

  @can('update', $thread)
    <div class="card-footer">
      <button type="submit" class="btn btn-warning" @click="editing = true">Edit</button>
    </div>
  @endcan
</div>