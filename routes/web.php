<?php
use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

// Route::middleware(['IsGuest'])->group(function () {
//     Route::get("/", function () {
//         return view('users.login');
//     })->name('login');
//     Route::post('/login', [UserController::class, 'loginAuth'])->name('login.auth');
// });

Route::get('/', function() {
    return view('users.login');
})->name('login');
Route::post('/login', [UserController::class, 'loginAuth'])->name('login.auth');

Route::middleware(['IsGuest'])->group(function() {
    Route::get('/error-permission', function () {
        return view('errors.permission');
    })->name('error.permission');
});

Route::middleware(['IsLogin', 'IsAdmin'])->group(function() {
    Route::get('/home', [Controller::class, 'landing'])->name('home.page');

    Route::prefix('/medicines')->name('medicines.')->group(function () {
        Route::get('/add', [MedicineController::class, 'create'])->name('create');
        Route::post('/add', [MedicineController::class, 'store'])->name('store');
        Route::get('/', [MedicineController::class, 'index'])->name('index');
        Route::delete('/delete/{id}', [MedicineController::class, 'destroy'])->name('delete');
        Route::get('/edit/{id}', [MedicineController::class, 'edit'])->name('edit');
        Route::patch('/medicine/edit/{id}', [MedicineController::class, 'update'])->name('update');
    });

    Route::prefix('/user')->name('user.')->group(function() {
        Route::get('/users', [UserController::class, 'index'])->name('index'); // User index for admin
        Route::get('/users/create', [UserController::class, 'create'])->name('create'); // Proper naming
        Route::post('/users', [UserController::class, 'store'])->name('store'); // Proper naming
        Route::get('/users/edit/{id}', [UserController::class, 'edit'])->name('edit'); // Proper naming
        Route::patch('/users/{id}', [UserController::class, 'update'])->name('update'); // Proper naming
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('delete'); // Proper naming
    }); 

    Route::prefix('/order')->name('order.')->group(function() {
        Route::prefix('/admin')->name('admin.')->group(function() {
            Route::get('/', [OrderController::class, 'index'])->name('index');
        });
    });
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
});

Route::middleware(['IsLogin', 'IsKasir'])->group(function() {
    Route::prefix('/kasir')->name('kasir.')->group(function() {
        Route::prefix('/order')->name('order.')->group(function() {
            Route::get('/', [OrderController::class, 'index'])->name('index');
            Route::get('/create', [OrderController::class, 'create'])->name('create');
            Route::post('/store', [OrderController::class, 'store'])->name('store');
            Route::get('/print/{id}', [OrderController::class, 'show'])->name('print');
            Route::get('/print_pdf/{id}', [OrderController:: class, 'downloadPdf'])->name('printPdf');
        });
    });
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
});

// Route::middleware(['Islogin'])->group(function () {
//     Route::get('/home', [Controller::class, 'landing'])->name('home.page');
//     Route::post('/logout', [UserController::class, 'logout'])->name('logout');

//     Route::middleware(['IsAdmin'])->group(function () {
//         Route::get('/users', [UserController::class, 'index'])->name('user.index'); // User index for admin
//         Route::get('/users/create', [UserController::class, 'create'])->name('user.create'); // Proper naming
//         Route::post('/users', [UserController::class, 'store'])->name('user.store'); // Proper naming
//         Route::get('/users/edit/{id}', [UserController::class, 'edit'])->name('user.edit'); // Proper naming
//         Route::patch('/users/{id}', [UserController::class, 'update'])->name('user.update'); // Proper naming
//         Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('user.delete'); // Proper naming
        
//         Route::prefix('/medicines')->name('medicines.')->group(function () {
//             Route::get('/add', [MedicineController::class, 'create'])->name('create');
//             Route::post('/add', [MedicineController::class, 'store'])->name('store');
//             Route::get('/', [MedicineController::class, 'index'])->name('index');
//             Route::delete('/delete/{id}', [MedicineController::class, 'destroy'])->name('delete');
//             Route::get('/edit/{id}', [MedicineController::class, 'edit'])->name('edit');
//             Route::patch('/medicine/edit/{id}', [MedicineController::class, 'update'])->name('update');
//         });
//     });
// });
