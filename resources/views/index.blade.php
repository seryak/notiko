@extends('layout')

@section('content')
    <div id="app">
        @foreach($notes as $note)
            <note :note="{{$note}}"></note>
        @endforeach
    </div>
@endsection