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

Route::get('/', ['as' => 'frontpage', 'uses' => 'FrontpageController@frontpage']);

Route::get('/crawlTeams', ['as' => 'crawlTeams', 'uses' => 'Webcrawl\TeamCrawlerController@crawlTeams']);

Route::get('/crawlTournamentsPremierEvents', ['as' => 'crawlTournamentsPremierEvents', 'uses' => 'Webcrawl\TournamentCrawlerController@crawlTournamentsPremierEvents']);
Route::get('/crawlTournamentsMajorEvents', ['as' => 'crawlTournamentsMajorEvents', 'uses' => 'Webcrawl\TournamentCrawlerController@crawlTournamentsMajorEvents']);
Route::get('/crawlTournamentsQualifiers', ['as' => 'crawlTournamentsQualifiers', 'uses' => 'Webcrawl\TournamentCrawlerController@crawlTournamentsQualifiers']);
Route::get('/crawlTournamentsMinorEvents', ['as' => 'crawlTournamentsMinorEvents', 'uses' => 'Webcrawl\TournamentCrawlerController@crawlTournamentsMinorEvents']);

Route::get('/crawlTournamentsMatches', ['as' => 'crawlTournamentsMatches', 'uses' => 'Webcrawl\MatchCrawlerController@crawlTournamentsMatches']);

Route::get('/crawlPlayers', ['as' => 'crawlPlayers', 'uses' => 'Webcrawl\PlayerCrawlerController@crawlPlayers']);
Route::get('/crawlPlayersImage', ['as' => 'crawlPlayersImage', 'uses' => 'Webcrawl\PlayerCrawlerController@crawlPlayersImage']);
Route::get('/crawlPlayersMMR', ['as' => 'crawlPlayersMMR', 'uses' => 'Webcrawl\PlayerCrawlerController@crawlPlayersMMR']);

Route::get('/crawlLiveScore', ['as' => 'crawlLiveScore', 'uses' => 'Webcrawl\LiveScoreController@crawlLiveScore']);

// Players >Rankings controller
//Route::get('/getPlayersRankingsAmericas', ['as' => 'getPlayersRankingsAmericas', 'uses' => 'PlayerRankingController@getPlayersRankingsAmericas']);
//Route::get('/getPlayersRankingsEurope', ['as' => 'getPlayersRankingsEurope', 'uses' => 'PlayerRankingController@getPlayersRankingsEurope']);
//Route::get('/getPlayersRankingsSEA', ['as' => 'getPlayersRankingsSEA', 'uses' => 'PlayerRankingController@getPlayersRankingsSEA']);
//Route::get('/getPlayersRankingsChina', ['as' => 'getPlayersRankingsChina', 'uses' => 'PlayerRankingController@getPlayersRankingsChina']);



// Login Routes...
Route::get('login', ['as' => 'login', 'uses' => 'Auth\LoginController@showLoginForm']);
Route::post('login', ['as' => 'login.post', 'middleware' => 'throttle:30', 'uses' => 'Auth\LoginController@login']);
Route::get('logaf', ['as' => 'logout', 'uses' => 'Auth\LoginController@logout']);

// Registration Routes...
Route::get('opret', ['as' => 'register', 'uses' => 'Auth\RegisterController@showRegistrationForm']);
Route::post('opret', ['as' => 'register.post', 'middleware' => 'throttle:10', 'uses' => 'Auth\RegisterController@register']);

// Password Reset Routes...
Route::get('password/reset', ['as' => 'password.reset', 'uses' => 'Auth\ForgotPasswordController@showLinkRequestForm']);
Route::post('password/email', ['as' => 'password.email', 'middleware' => 'throttle:5', 'uses' => 'Auth\ForgotPasswordController@sendResetLinkEmail']);
Route::get('password/reset/{token}', ['as' => 'password.reset.token', 'uses' => 'Auth\ResetPasswordController@showResetForm']);
Route::post('password/reset', ['as' => 'password.reset.post', 'middleware' => 'throttle:5', 'uses' => 'Auth\ResetPasswordController@reset']);

Route::middleware(['isActiveUser', 'isAdminUser'])->group(function () {

        Route::get('admin/dashboard', ['as' => 'admin.dashboard', 'uses' => 'Admin\DashboardController@dashboard']);


        // Admin backend TEAMS routes...
        Route::get('admin/teams', ['as' => 'admin.teams.index', 'uses' => 'Admin\TeamController@index']);
        Route::get('admin/teams/create', ['as' => 'admin.teams.create', 'uses' => 'Admin\TeamController@create']);
        Route::post('admin/teams/store', ['as' => 'admin.teams.store', 'uses' => 'Admin\TeamController@store']);
        Route::delete('admin/teams/destroy', ['as' => 'admin.teams.destroy.all', 'uses' => 'Admin\TeamController@destroyAll']);
        Route::get('admin/teams/{id}/edit', ['as' => 'admin.teams.edit', 'uses' => 'Admin\TeamController@edit']);
        Route::patch('admin/teams/{id}/update', ['as' => 'admin.teams.update', 'uses' => 'Admin\TeamController@update']);
        Route::delete('admin/teams/{id}/destroy', ['as' => 'admin.teams.destroy', 'uses' => 'Admin\TeamController@destroy']);

        // Admin backend PLAYERS routes...
        Route::get('admin/players', ['as' => 'admin.players.index', 'uses' => 'Admin\PlayerController@index']);
        Route::get('admin/players/create', ['as' => 'admin.players.create', 'uses' => 'Admin\PlayerController@create']);
        Route::post('admin/players/store', ['as' => 'admin.players.store', 'uses' => 'Admin\PlayerController@store']);
        Route::delete('admin/players/destroy', ['as' => 'admin.players.destroy.all', 'uses' => 'Admin\PlayerController@destroyAll']);
        Route::get('admin/players/{id}/edit', ['as' => 'admin.players.edit', 'uses' => 'Admin\PlayerController@edit']);
        Route::patch('admin/players/{id}/update', ['as' => 'admin.players.update', 'uses' => 'Admin\PlayerController@update']);
        Route::delete('admin/players/{id}/destroy', ['as' => 'admin.players.destroy', 'uses' => 'Admin\PlayerController@destroy']);

        // Admin backend TOURNAMENTS routes...
        Route::get('admin/tournaments', ['as' => 'admin.tournaments.index', 'uses' => 'Admin\TournamentController@index']);
        Route::get('admin/tournaments/create', ['as' => 'admin.tournaments.create', 'uses' => 'Admin\TournamentController@create']);
        Route::post('admin/tournaments/store', ['as' => 'admin.tournaments.store', 'uses' => 'Admin\TournamentController@store']);
        Route::delete('admin/tournaments/destroy', ['as' => 'admin.tournaments.destroy.all', 'uses' => 'Admin\TournamentController@destroyAll']);
        Route::get('admin/tournaments/{id}/edit', ['as' => 'admin.tournaments.edit', 'uses' => 'Admin\TournamentController@edit']);
        Route::patch('admin/tournaments/{id}/update', ['as' => 'admin.tournaments.update', 'uses' => 'Admin\TournamentController@update']);
        Route::delete('admin/tournaments/{id}/destroy', ['as' => 'admin.tournaments.destroy', 'uses' => 'Admin\TournamentController@destroy']);

});