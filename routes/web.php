<?php

use Illuminate\Support\Facades\Route;

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
use App\Http\Controllers\Admin\{AuthController,ProfileController,UserController,VehiclesControllers,BookingsControllers};

Route::get('/', function () {
    return view('home');
});

Route::get('/admin/login',[AuthController::class,'getLogin'])->name('getLogin');
Route::post('/admin/login',[AuthController::class,'postLogin'])->name('postLogin');

Route::get('/admin/register',[AuthController::class,'register'])->name('register');
Route::post('/admin/register',[AuthController::class,'registerStore'])->name('register.store');

Route::get('/user/login',[UserController::class,'userGetLogin'])->name('user.getLogin');
Route::post('/user/login',[UserController::class,'userPostLogin'])->name('user.postLogin');

Route::get('/user/register',[UserController::class,'userRegister'])->name('user.register');
Route::post('/user/register',[UserController::class,'userRegisterStore'])->name('user.register.store');

Route::group(['middleware'=>['admin_auth']],function(){
    
    Route::get('/admin/dashboard',[ProfileController::class,'dashboard'])->name('dashboard');
    Route::get('/admin/users',[UserController::class,'index'])->name('users.index');
    Route::get('/admin/logout',[ProfileController::class,'logout'])->name('logout');

    Route::get('/vehicles', [VehiclesControllers::class, 'index'])->name('admin.vehicles.index');
    Route::get('/vehicles/create', [VehiclesControllers::class, 'create'])->name('admin.vehicles.create');
    Route::post('/vehicles', [VehiclesControllers::class, 'store'])->name('admin.vehicles.store');
    Route::get('/vehicles/edit/{id}', [VehiclesControllers::class, 'edit'])->name('admin.vehicles.edit');
    Route::post('/vehicles/update/{id}', [VehiclesControllers::class, 'update'])->name('admin.vehicles.update');

    Route::delete('/vehicles/{id}', [VehiclesControllers::class, 'destroy'])->name('admin.vehicles.destroy');

    Route::get('/bookings', [BookingsControllers::class, 'showBookings'])->name('admin.bookings.index');
    Route::get('/bookings/create', [BookingsControllers::class, 'create'])->name('admin.bookings.create');
    Route::post('/bookings', [BookingsControllers::class, 'store'])->name('admin.bookings.store');

    Route::get('/admin/bookings', [BookingsControllers::class, 'show'])->name('admin.bookings.show');
    // Route::get('/admin/bookings/{booking}/edit', [AdminController::class, 'editBooking'])->name('admin.bookings.edit');
    // Route::put('/admin/bookings/{booking}', [AdminController::class, 'updateBooking'])->name('admin.bookings.update');









});
