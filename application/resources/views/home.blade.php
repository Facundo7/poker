@extends('layouts.skeleton')

@section('content')
<a href="{{ route('login') }}">Login</a>
<a href="{{route('tournaments.index')}}">View tournament</a>
<a href="{{route('tournaments.create')}}">Create tournament</a>
<br>
<a href="{{ route('logout') }}"
    onclick="event.preventDefault();
    document.getElementById('logout-form').submit();">
     {{ __('Logout') }}
    </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
        </form>
@endsection
