@forelse ($threads as $thread)
  @include('threads._thread')
@empty
<p>There are no relevant results at the time.</p>
@endforelse
