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

Auth::routes();


Route::group(['middleware' => 'auth'],function() {

    Route::get('/', function () {
        return view('home');
    });

    Route::get('/home', 'HomeController@index')->name('home');

    Route::resource('tournaments', 'TournamentController');

    Route::prefix('api')->name('api.')->group(function () {


        Route::prefix('tournaments')->name('tournaments.')->group(function () {

            Route::get('{tournament}/round', 'Api\TournamentController@currentRound')->name('round');
            Route::get('{tournament}/betround', 'Api\TournamentController@currentBetRound')->name('betround');
            Route::get('{tournament}/playerlogged', 'Api\TournamentController@playerLogged')->name('playerlogged');
            Route::get('{tournament}/players', 'Api\TournamentController@players')->name('players');

        });

        Route::apiResource('tournaments', 'Api\TournamentController')->only([
            'index', 'show'
        ]);
        Route::apiResource('players', 'Api\PlayerController')->only([
            'index', 'store'
        ]);
        Route::apiResource('actions', 'Api\ActionController')->only([
            'store'
        ]);
    });


});



