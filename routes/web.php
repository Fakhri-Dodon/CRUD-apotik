<?php
use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MedicineController;
use Illuminate\Support\Facades\Route;

Route::get("/", function () {
    return view('users.login');
})->name('login');

Route::post('/login', [UserController::class, 'loginAuth'])->name('login.auth');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');
Route::get('/home', [Controller::class, 'landing'])->name('home.page');
Route::get('/error-permission', function () {
    return view('errors.permission');
})->name('error.permission');


// Define the home route in one place
Route::middleware(['lslogin', 'lsadmin'])->group(function () {
    Route::get('/users', function () {
        return view('home');
    })->name('home.page');

    Route::prefix('/medicines')->name('medicines.')->group(function () {
        Route::get('/add', [MedicineController::class, 'create'])->name('create');
        Route::post('/add', [MedicineController::class, 'store'])->name('store');
        Route::get('/', [MedicineController::class, 'index'])->name('index');
        Route::delete('/delete/{id}', [MedicineController::class, 'destroy'])->name('delete');
        Route::get('/edit/{id}', [MedicineController::class, 'edit'])->name('edit');
        Route::patch('/medicine/edit/{id}', [MedicineController::class, 'update'])->name('update');
    });
    
    Route::prefix('/users')->name('user.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
        Route::patch('/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('delete');
    });
});

Route::middleware(['lslogin', 'lsuser'])->group(function () {
    Route::get('/users', function () {
        return view('login');
    })->name('users.login');
});
