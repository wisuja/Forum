@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mt-3">
                <div class="card-header">
                    <a href="#">{{ $thread->creator->name }}</a> posted:
                    {{ $thread->title }}
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
        </div>
    </div>
    <div class="row justify-content-center mb-3">
        <div class="col-md-8">
            @foreach ($thread->replies as $reply)
                @include('threads.reply')
            @endforeach
        </div>
    </div>
    @if (auth()->check())
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form action="{{ $thread->path() }}/replies" method="POST">
                    @csrf
                    <div class="form-group">
                        <textarea name="body" id="body" class="form-control" rows="5" placeholder="Have something to say?"></textarea>    
                    </div>
                    <button type="submit" class="btn btn-primary">Post</button>
                </form>
            </div>
        </div>
    @else
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <p>You need to <a href="{{ route('login') }}">sign in</a> to participate in the thread.</p>
            </div>
        </div>
    @endif
</div>
@endsection
