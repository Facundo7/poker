@extends('layouts.skeleton')
@section('content')
<div class="card form-card">
    <div class="card-header">
        <h3 class="form-title">New tournament</h3>
    </div>
    <form action="/tournaments" method="post">
        @csrf
        <div class="label">title: </div>
        <input class="form-control" type="text" name="title" value="{{ old('title') }}">
        <div class="label">BB: </div>
        <input class="form-control" type="text" name="bb" value="{{ old('bb') }}">
        <div class="label">BB start value: </div>
        <input class="form-control" type="text" name="bb_start_value" value="{{ old('bb_start_value') }}">
        <div class="label">bb_increase_time: </div>
        <input class="form-control" type="text" name="bb_increase_time" value="{{ old('bb_increase_time') }}">
        <div class="label">bb_increase_value: </div>
        <input class="form-control" type="text" name="bb_increase_value" value="{{ old('bb_increase_value') }}">
        <div class="label">initial_stack: </div>
        <input class="form-control" type="text" name="initial_stack" value="{{ old('initial_stack') }}">
        <div class="label">turn_seconds: </div>
        <input class="form-control" type="text" name="turn_seconds" value="{{ old('turn_seconds') }}">
        <div class="label">buy_in: </div>
        <input class="form-control" type="text" name="buy_in" value="{{ old('buy_in') }}">

        <div><input type="submit" value="Create tournament" class="btn btn-primary form-submit"></div>
    </form>
</div>
@endsection
