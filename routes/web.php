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
    return view('welcome');
});
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
        })->name('index');
        Route::get('todo', function () {
            return view('app.todo');
        })->name('todo');
        Route::get('callendar', function () {
            return view('app.callendar');
        })->name('callendar');
        Route::get('chat', function () {
            return view('app.chat');
        })->name('chat');

    });

Route::prefix('user')
    ->as('user.')
    ->group(function (){
        Route::get('/login', function () {
            return view('user.login');
        })->name('login');
        Route::get('/sign-up', function () {
            return view('user.sign-up');
        })->name('sign.up');

    });
