<?php

use Illuminate\Support\Facades\Route;  
use App\Http\Controllers\UserController;
use App\Http\Controllers\MainController;

// Register New User
Route::get('/register',[UserController::class,'registerpage']);
Route::post('/register',[UserController::class,'saveUser'])->name('auth.register');

// Forgotten Password
Route::get('/forget',[UserController::class,'forgetpage']);
Route::post('/forget',[UserController::class,'forgetPassword'])->name('forget.password');

// Reset Password
Route::get('reset/{email}/{token}',[UserController::class,'resetpage'])->name('reset');
Route::post('/reset-password',[UserController::class,'resetPassword'])->name('reset.password');

// Check User
Route::post('/login',[UserController::class,'checkUser'])->name('auth.login');

// Delete User
Route::post('/users-delete/{id}',[MainController::class,'userDelete'])->name('delete.user');
// Route::match(['get', 'post'], '/users-delete/{id}', [MainController::class, 'userDelete']);

// City Store
Route::post('/city/insert',[MainController::class,'cityInsert']);
Route::post('/city-delete/{id}',[MainController::class,'cityDelete']);

// Category
Route::post('/category/insert',[MainController::class,'categoryInsert']);
Route::post('/category-deleted/{id}',[MainController::class,'categoryDeleted']);
Route::match(['get', 'post'], '/categoryRestore/{id}', [MainController::class, 'categoryRestore']);

// Expense Store
Route::post('/expense',[MainController::class,'expenseStore'])->name('store.expense');
Route::match(['get', 'post'], '/expencedeleted/{id}', [MainController::class, 'deleteExpenses']);
Route::match(['get', 'post'], '/expenceRestore/{id}', [MainController::class, 'restoreExpenses']);

// Middleware Checking URLs
Route::group(['middleware' => ['LoginCheck']], function()
    {    
        Route::get('/',[UserController::class,'loginpage']);
        Route::get('/logout',[UserController::class,'logoutUser'])->name('auth.logout');
        // Profile
        Route::get('/profile',[UserController::class,'profilepage'])->name('profile');
        Route::post('/profile-image',[UserController::class,'profileImageUpdate'])->name('profile.image');
        Route::post('/profile-update',[UserController::class,'profileUpdate'])->name('profile.update');
        // Dashboard
        Route::get('/dashboard',[MainController::class,'dashboardpage'])->name('dashboard');
        //  Customization
        Route::get('/customize',[MainController::class,'customizePage']);
        // City
        Route::get('/city',[MainController::class,'cityPage']);
        // Expense
        Route::get('/expense',[MainController::class,'expensePage'])->name('expense');
        // Users
        Route::get('/users',[MainController::class,'usersPage']);
        Route::get('/user-details/{id}',[MainController::class,'userDetailsPage']);
        // Category
        Route::get('/category',[MainController::class,'categoryPage']);
        // Trash
        Route::get('/trash',[MainController::class,'trashPage']);
    });

// Customize
Route::post('customize/Insert',[MainController::class,'customizeInsert']);
// Route::post('customize/Edit/{id}',[MainController::class,'customizeEdit']);
Route::match(['get', 'post'], 'customize/Edit/{id}', [MainController::class, 'customizeEdit']);
Route::match(['get', 'post'], 'customize/Update/{id}', [MainController::class, 'customizeUpdate']);


// Trash Permenantly Deleted
Route::match(['get', 'post'], '/expencePermenantDeleteExpense/{id}', [MainController::class, 'permenantTrashDeleteExpense']);
Route::match(['get', 'post'], '/categoryPermenantDeleteCategory/{id}', [MainController::class, 'permenantTrashDeleteCategory']);
