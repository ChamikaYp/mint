<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobController;

Route::get('/', function () {
    // return view('welcome');
    return redirect()->route('dashboard');
});

Route::get('/jobs', function () {
    // return view('jobs');
    if (Auth::check() && Auth::user()->admin) {
        return view('jobs');
    }
    return redirect()->route('dashboard');
})->middleware('auth')->name('jobs');

Route::get('/tickets', function () {
    return view('tickets');
})->middleware('auth')->name('tickets');
Route::get('/tickets/{id}', function ($id) {
    return view('ticket',  ['ticketId' => $id]);
})->middleware('auth')->name('ticket');

Route::get('/schedule', function () {
    return view('schedule');
})->middleware('auth')->name('schedule');

Route::get('/job-list', function () {
    return view('job_list');
})->middleware('auth')->name('job_list');;

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/profile', function () {
    return view('profile');
})->middleware(['auth', 'verified'])->name('profile');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route::resource('jobs', JobController::class);

require __DIR__.'/auth.php';
