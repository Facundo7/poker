@extends('layouts.skeleton')

@section('content')
<a href="{{route('tournaments.index')}}">View tournament</a>
<a href="{{route('tournaments.create')}}">Create tournament</a>
<tournamentList></tournamentList>
@endsection
