@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @include('threads._list')

            <div class="mt-3 text-center">
                {{ $threads->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
