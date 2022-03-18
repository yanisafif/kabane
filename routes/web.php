<?php

use App\Http\Controllers\AuthController;

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
    return view('welcome');
})->name('index.international');
// Route::get('/kanban', function () {
//     return view('app.kanban');
// });
// Route::get('/kanban/to-do', function () {
//     return view('app.todo')->name('todo');
// });

Route::prefix('kanban')
    ->as('kanban.')
    ->group(function (){
        Route::get('/', function () {
            return view('app.kanban');
        })->name('index')->middleware('auth');

        Route::get('create', function () {
            return view('app.create-kanban');
        })->name('create')->middleware('auth');

        Route::post('store', 'KanbanController@store')
        ->name('store')->middleware('auth');

        Route::get('todo', function () {
            return view('app.todo');
        })->name('todo')->middleware('auth');
        Route::get('callendar', function () {
            return view('app.callendar');
        })->name('callendar')->middleware('auth');
        Route::get('chat', function () {
            return view('app.chat');
        })->name('chat')->middleware('auth');

    });

Route::prefix('user')
    ->as('user.')
    ->group(function (){

        Route::get('/login', function () {
            return view('user.login');
        })->name('login')->middleware('guest');

        Route::post('post-login', [AuthController::class, 'postLogin'])->name('login.post')->middleware('guest');

        Route::get('/sign-up', function () {
            return view('user.sign-up');
        })->name('sign.up')->middleware('guest');

        Route::post('post-register', [AuthController::class, 'postRegistration'])->name('register.post')->middleware('guest');

        Route::get('logout', [AuthController::class, 'logOut'])->name('logout')->middleware('auth');
    });
