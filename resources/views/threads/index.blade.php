@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
					<h3>Forum Threads</h3>
					@foreach ($threads as $thread)
						<div class="card mt-3">
							<div class="card-header">
								<div class="level">
									<h5 class="flex">
										<a href="{{ $thread->path() }}">{{ $thread->title }}</a>
									</h5>
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
					@endforeach
      </div>
    </div>
</div>
@endsection
