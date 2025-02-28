@extends('layout')

@section('content')
    <div id="app">
        @foreach($notes as $note)
            <note :note="{{$note}}"></note>
        @endforeach
    </div>

    <div class="popup note-popup">
        <div class="content">
            <div class="popup-content"></div>
            <button class="close">×</button>
        </div>
    </div>
@endsection
