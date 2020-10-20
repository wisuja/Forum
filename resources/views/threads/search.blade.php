@extends('layouts.app')

@section('content')
<search :data-query="'{{ $query }}'"></search>
@endsection
