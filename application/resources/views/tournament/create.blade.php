@extends('layouts.skeleton')
@section('content')
<div class="card form-card">
    <div class="card-header">
        <h3 class="form-title">New tournament</h3>
    </div>
    <form action="/tournaments" method="post">
        @csrf
        <div class="label">Title: </div>
        <input class="form-control" type="text" name="title" value="{{ old('title') }}">
        <div class="label">Big Blind: </div>
        <input class="form-control" type="text" name="bb" value="{{ old('bb') }}">
        <div class="label">Inicial Stack: </div>
        <input class="form-control" type="text" name="initial_stack" value="{{ old('initial_stack') }}">
        <div class="label">duration of turn(in seconds): </div>
        <input class="form-control" type="text" name="turn_seconds" value="{{ old('turn_seconds') }}">
        <div class="label">Number of players: </div>
        <input class="form-control" type="text" name="players_number" value="{{ old('players_number') }}">
        <div class="label">Buy in: </div>
        <input class="form-control" type="text" name="buy_in" value="{{ old('buy_in') }}">

        <div><input type="submit" value="Create tournament" class="btn btn-primary form-submit"></div>
    </form>
</div>
@endsection
