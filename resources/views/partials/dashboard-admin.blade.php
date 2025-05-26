@extends('layouts.app')

@section('content')
    <div>{{ auth()->user()  }}</div>
@endsection