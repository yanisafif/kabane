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
        })->name('index');

        Route::get('create', function () {
            return view('app.create-kanban');
        })->name('create');

        Route::post('store', 'KanbanController@store')
        ->name('store');

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

        Route::post('post-login', [AuthController::class, 'postLogin'])->name('login.post');

        Route::get('/sign-up', function () {
            return view('user.sign-up');
        })->name('sign.up');

        Route::post('post-register', [AuthController::class, 'postRegistration'])->name('register.post');

        Route::get('logout', [AuthController::class, 'logOut'])->name('logout');
    });
