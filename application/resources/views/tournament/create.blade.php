@extends('layouts.skeleton')
@section('content')

<div class="create-form">
    <h3 class="title">New tournament</h3>

    <form action="/tournaments" method="post">
        @csrf
        <div class="label">Title: </div>
        <input class="form-control" style="width:30%" type="text" name="title" value="{{ old('title') }}">
        <div class="label">Big Blind: </div>
        <input class="form-control" style="width:30%" type="number" name="bb" value="{{ old('bb') }}">
        <div class="label">Inicial Stack: </div>
        <input class="form-control" style="width:30%" type="text" name="initial_stack" value="{{ old('initial_stack') }}">
        <div class="label">duration of turn(in seconds): </div>
        <input class="form-control" style="width:30%" type="text" name="turn_seconds" value="{{ old('turn_seconds') }}">
        <div class="label">Number of players: </div>
        <input class="form-control" style="width:30%" type="text" name="players_number" value="{{ old('players_number') }}">
        <div class="label">Buy in: </div>
        <input class="form-control" style="width:30%" type="text" name="buy_in" value="{{ old('buy_in') }}">

        <div><input type="submit" value="Create tournament" class="btn create-btn form-submit"></div>
    </form>

</div>
    @if ($errors->any())
    <div class="error">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@endsection

