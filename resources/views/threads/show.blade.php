@extends('layouts.app')

@section('head')
<link rel="stylesheet" href="{{ asset('css/vendor/jquery.atwho.css') }}">
@endsection

@section('content')
<thread :initial-replies-count="{{ $thread->replies_count }}" inline-template>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card mt-3">
                    <div class="card-header">
                        <div class="level">
                            <h5 class="flex">
                                <img src="{{ $thread->creator->avatar_path }}" alt="" width="25" height="25">
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
    
                <replies @created="repliesCount++" @removed="repliesCount--"></replies>
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
                            <a href="{{ route('profile',$thread->creator) }}">{{ $thread->creator->name }}</a>, and currently have <span v-text="repliesCount"></span> {{ Str::plural('comment', $thread->replies_count) }}.
                        </p>

                        <p>
                            <subscribe-button :active="{{ json_encode($thread->isSubscribedTo) }}"></subscribe-button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</thread>
@endsection
