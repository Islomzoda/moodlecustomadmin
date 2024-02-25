<?php

use App\Services\Telegram\Telegram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('moodle/create', [\App\Services\Moodle\Moodle::class, 'create_user']);
Route::post('importMoodleUsers', [\App\Services\Moodle\Moodle::class, 'importMoodleUsers']);
Route::post('/remove_user', [Telegram::class, 'removeUser']);
Route::post('/add_user', [Telegram::class, 'addUser']);
Route::post('mpstats/access', [\App\Services\Mpstats\Mpstats::class, 'access']);
Route::get('/check/{id}', [Telegram::class, 'check']);


