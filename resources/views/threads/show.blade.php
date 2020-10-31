@extends('layouts.app')

@section('head')
@if (env('APP_ENV') == "local")
    <link rel="stylesheet" href="{{ asset('css/vendor/jquery.atwho.css') }}">
@else
    <link rel="stylesheet" href="{{ secure_asset('css/vendor/jquery.atwho.css') }}">    
@endif
@endsection

@section('content')
<thread :thread="{{ $thread}}" inline-template>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                @include('threads._question')
    
                <replies @created="updateFormPanel" @removed="repliesCount--"></replies>
            </div>
    
            <div class="col-md-4">
                <div class="card mt-3">
                    <div class="card-body">
                        <p>
                            The thread was created on {{ $thread->created_at->diffForHumans() }} by
                            <a href="{{ route('profile',$thread->creator) }}">{{ $thread->creator->name }}</a>, and currently have <span v-text="repliesCount"></span> {{ Str::plural('comment', $thread->replies_count) }}.
                        </p>

                        <p>
                            <subscribe-button :active="{{ json_encode($thread->isSubscribedTo) }}" v-if="signedIn"></subscribe-button>
                            <button type="submit" class="btn btn-outline-danger ml-3" v-if="authorize('isAdmin')" @click="toggleLock" v-text="locked ? 'Unlock' : 'Lock'"></button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</thread>
@endsection
