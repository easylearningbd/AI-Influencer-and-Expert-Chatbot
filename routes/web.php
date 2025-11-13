<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsUser;

Route::get('/', function () {
    return view('welcome');
});


// All routes for Role User Only
Route::middleware(['auth',IsUser::class])->group(function(){ 

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


});
// End Role User Route 

// All routes for Role Admin Only
Route::middleware(['auth',IsAdmin::class])->group(function(){

Route::get('/admin/dashboard', [AdminController::class, 'AdminDashboard'])->name('admin.dashboard');
Route::get('/admin/logout', [AdminController::class, 'AdminLogout'])->name('admin.logout');

});
// End Role Admin Route 





Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
