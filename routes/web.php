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
        Route::get('board/{id?}', 'KanbanController@board')
            ->name('board');

        Route::get('create', 'KanbanController@create')
            ->name('create');

        Route::post('store', 'KanbanController@store')
            ->name('store');

        Route::get('todo/{id?}', function () {
            return view('app.todo');
        })->name('todo');
        Route::get('callendar/{id?}', function () {
            return view('app.callendar');
        })->name('callendar');
        Route::get('chat/{id?}', function () {
            return view('app.chat');
        })->name('chat');

    });

Route::prefix('item')
    ->as('item.')
    ->group(function (){
        Route::post('store', 'ItemController@store')
            ->name('store');

        Route::delete('delete', 'ItemController@delete')
            ->name('delete');

        Route::put('update', 'ItemController@update')
            ->name('update');

        Route::put('move', 'ItemController@move')
            ->name('move');
    });

Route::prefix('col')
    ->as('col.')
    ->group(function (){
        Route::post('add', 'ColController@add')
            ->name('add');

        Route::put('edit', 'ColController@edit')
            ->name('edit');

        Route::put('move', 'ColController@move')
            ->name('move');
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
