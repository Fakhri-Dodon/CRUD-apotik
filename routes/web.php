<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MedicineController;
use Illuminate\Support\Facades\Route;

Route::get("/", function () {
    return view('users.login'); // This is the GET route for showing the login form
})->name('login');

Route::post('/login', [UserController::class, 'loginAuth'])->name('login.auth'); // This is the POST route for login

Route::get('/home', [Controller::class, 'landing'])->name('home.page'); // Define the home route

Route::post('/logout', [UserController::class, 'logout'])->name('logout');


Route::prefix('/medicines')->name('medicines.')->group(function(){
    Route::get('/add', [MedicineController::class, 'create'])->name('create');
    Route::post('/add', [MedicineController::class, 'store'])->name('store');
    Route::get('/', [MedicineController::class, 'index'])->name('index');
    Route::delete('/delete/{id}', [MedicineController::class, 'destroy'])->name('delete');
    Route::get('/edit/{id}', [MedicineController::class, 'edit'])->name('edit');
    Route::patch('/medicine/edit/{id}', [MedicineController::class, 'update'])->name('update');
});

Route::prefix('/users')->name('user.')->group(function() {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/create', [UserController::class, 'create'])->name('create');
    Route::post('/', [UserController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
    Route::patch('/{id}', [UserController::class, 'update'])->name('update');
    Route::delete('/{id}', [UserController::class, 'destroy'])->name('delete');
});


