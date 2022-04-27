<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;

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
})->name('index.international')->middleware('guest');
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

        Route::post('invite', 'KanbanController@invite')
            ->name('invite');

        Route::delete('uninvite', 'KanbanController@uninvite')
            ->name('uninvite');

        Route::delete('self-uninvite', 'KanbanController@selfUninvite')
            ->name('selfUninvite');

        Route::get('chat/{id?}', 'MessageController@index')
            ->name('chat');

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

        Route::delete('delete', 'ColController@delete')
            ->name('delete');
    });

Route::prefix('message')
    ->as('message.')
    ->group(function (){
        Route::post('add', 'MessageController@add')
            ->name('add');
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

        Route::get('/send-mail', function () {
            return view('user.send-mail-password');
        })->name('password.reset')->middleware('guest');

        Route::post('post-reset', [AuthController::class, 'resetPassword'])->name('reset.password')->middleware('guest');

        Route::get('/reset/password/{uuid}', [AuthController::class, 'getResetPasswordWithUuid'])
            ->name('password.reset.uuid')->middleware('guest');

        Route::post('post-reset-uuid', [AuthController::class, 'postResetPasswordWithUuid'])->name('reset.password.uuid')->middleware('guest');

        Route::get('/profile/{name}', [UserController::class, 'getUser'])
        ->name('user.profile')->middleware('auth');

        Route::get('/profile/update/{id}', [UserController::class, 'getUpdateUser'])
        ->name('user.update')->middleware('auth');
        Route::get('/profile/delete/avatar/{id}', [UserController::class, 'getDeleteAvatar'])
        ->name('delete.avatar')->middleware('auth');

        Route::post('post-update-avatar', [UserController::class, 'postUpdateAvatar'])->name('update.avatar')->middleware('auth');
        Route::post('post-update-name', [UserController::class, 'postEditUserName'])->name('update.name')->middleware('auth');
        Route::post('post-update-email', [UserController::class, 'postEditEmail'])->name('update.email')->middleware('auth');
        Route::post('post-update-link', [UserController::class, 'postEditUserLinkInformation'])->name('update.link')->middleware('auth');
        Route::post('post-update-information', [UserController::class, 'postEditUserSecondaryInformation'])->name('update.information')->middleware('auth');
        Route::post('post-update-password', [UserController::class, 'postEditUserPassword'])->name('update.password')->middleware('auth');
        Route::post('user.delete.user', [UserController::class, 'postDeleteUser'])->name('delete.user')->middleware('auth');
    });

Route::prefix('admin')
    ->as('admin.')
    ->group(function (){
        Route::get('/panel', [AdminController::class, 'panel'])
            ->name('panel')->middleware('auth');
    });
