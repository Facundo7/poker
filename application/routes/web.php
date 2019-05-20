<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('home');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('tournaments', 'TournamentController');

Route::prefix('api')->name('api.')->group(function () {


    Route::get('players/logged/{tournament_id}', 'Api\PlayerController@logged')->name('players.logged');
    Route::get('players/loggedcards/{tournament_id}', 'Api\PlayerController@loggedCards')->name('players.loggedcards');
    Route::get('tournaments/{tournament_id}/boardcards', 'Api\TournamentController@boardCards')->name('tournaments.boardcards');
    Route::apiResource('tournaments', 'Api\TournamentController');
    Route::apiResource('players', 'Api\PlayerController');
});

