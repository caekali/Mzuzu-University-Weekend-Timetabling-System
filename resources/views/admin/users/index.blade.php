@extends('layouts.app')

@section('content')
    @php
        $headers = ['id' => 'ID', 'name' => 'Name', 'email' => 'Email'];
        $users = [
            ['ID' => 1, 'Name' => 'Alice', 'Email' => 'alice@example.com'],
            ['ID' => 2, 'Name' => 'Bob', 'Email' => 'bob@example.com'],
        ];

    @endphp
    <x-table :headers="$headers" :rows="$users" :actions="false" :paginate="false" />
@endsection
