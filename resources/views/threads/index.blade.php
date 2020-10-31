@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            @include('threads._list')

            <div class="mt-3 text-center">
                {{ $threads->links() }}
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header">
                    Search
                </div>
                <div class="card-body">
                    <form action="/threads/search" method="GET">
                        <div class="form-group">
                            <input type="text" name="q" placeholder="Search..." class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary">Search</button>
                    </form>
                </div>
            </div>

            @if (count($trending))
            <div class="card">
                <div class="card-header">
                    Trending pages
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach ($trending as $thread)
                        <li class="list-group-item">
                            <a href="{{ url($thread->path()) }}">{{ $thread->title }}</a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
