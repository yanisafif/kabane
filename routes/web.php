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
        Route::get('/{id?}', 'KanbanController@index')
            ->name('index');

        Route::get('create', function () {
            return view('app.create-kanban');
        })->name('create');

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
