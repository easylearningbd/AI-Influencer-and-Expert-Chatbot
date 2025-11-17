<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsUser;
use App\Http\Controllers\Admin\PlanController;

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
Route::get('/admin/profile', [AdminController::class, 'AdminProfile'])->name('admin.profile');
Route::post('/admin/profile/store', [AdminController::class, 'AdminProfileStore'])->name('admin.profile.store');

Route::get('/admin/change/password', [AdminController::class, 'AdminChangePassword'])->name('admin.change.password');
Route::post('/admin/password/update', [AdminController::class, 'AdminPasswordUpdate'])->name('admin.password.update');


Route::controller(PlanController::class)->group(function(){

Route::get('/admin/plans/index', 'PlanIndex')->name('admin.plans.index');
Route::get('/admin/plans/create', 'PlanCreate')->name('admin.plans.create');
Route::post('/admin/plans/store', 'PlanStore')->name('admin.plans.store');    
Route::get('/admin/plans/{plan}/edit', 'PlanEdit')->name('admin.plans.edit');
Route::put('/admin/plans/{plan}/update', 'PlanUpdate')->name('admin.plans.update');

});







});
// End Role Admin Route 





Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
