<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;




//home
// Route::get('/home', [HomeController::class, 'index'])->name('home');



Auth::routes();

Route::get('/', function () {
    return view('welcome');
});



Route::get(
    '/dashboard',
    function () {
        return view('dashboard');
    }
)->middleware(['auth'])->name('dashboard');



require __DIR__ . '/auth.php';


//rouets admin

Route::group(['middleware' => ['auth', 'can:manage-users']], function () {

    Route::resource('admin/permissions', PermissionController::class);
    Route::resource('admin/roles', RoleController::class);
    Route::resource('admin/users', UserController::class);
});
