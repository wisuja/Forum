@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card mt-3">
                <div class="card-header">
                    <div class="level">
                        <h5 class="flex">
                            <a href="{{ route('profile',$thread->creator) }}">{{ $thread->creator->name }}</a> posted:
                            {{ $thread->title }}
                        </h5>
                         @can('update', $thread)
                            <form action="{{ $thread->path() }}" method="POST">
                                @method('DELETE')
                                @csrf
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        @endcan
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

            @foreach ($replies as $reply)
                @include('threads.reply')
            @endforeach

            <div class="mt-3">
                {{ $replies->links() }}
            </div>

            @if (auth()->check())
                <form action="{{ $thread->path() }}/replies" method="POST" class="mt-3">
                    @csrf
                    <div class="form-group">
                        <textarea name="body" id="body" class="form-control" rows="5" placeholder="Have something to say?"></textarea>    
                    </div>
                    <button type="submit" class="btn btn-primary">Post</button>
                </form>
            @else
                <p>You need to <a href="{{ route('login') }}">sign in</a> to participate in the thread.</p>
            @endif
        </div>

        <div class="col-md-4">
            <div class="card mt-3">
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <p>
                        The thread was created on {{ $thread->created_at->diffForHumans() }} by
                        <a href="#">{{ $thread->creator->name }}</a>, and currently have {{ $thread->replies_count }} {{ Str::plural('comment', $thread->replies_count) }}.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
