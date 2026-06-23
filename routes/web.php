<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CurrencyRateController;
use App\Http\Controllers\DueController;
use App\Http\Controllers\FixedCostController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SalaryReportController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransactionTypeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])
         ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
         ->middleware('password.confirm')
         ->name('profile.update');

    Route::put('/password', [ProfileController::class, 'updatePassword'])
         ->middleware(['throttle:6,1'])
         ->name('profile.password.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
         ->middleware(['throttle:3,1', 'password.confirm'])
         ->name('profile.destroy');

    Route::resource('transactions', TransactionController::class);

    Route::resource('accounts', AccountController::class)->except(['show']);

    Route::resource('transaction-types', TransactionTypeController::class)->except(['show']);

    Route::resource('categories', CategoryController::class)->except(['show']);

    Route::resource('currency-rates', CurrencyRateController::class)->except(['show']);

    Route::resource('dues', DueController::class)->except(['show']);

    Route::resource('contacts', ContactController::class)->except(['show']);

    Route::resource('fixed-costs', FixedCostController::class)->except(['show']);

    Route::get('/salary-report', [SalaryReportController::class, 'index'])->name('salary-report.index');

    Route::resource('loans', LoanController::class)->except(['show']);

    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

    Route::get('/report', [ReportController::class, 'index'])->name('report.index');

    Route::resource('businesses', BusinessController::class)->except(['show']);

    Route::get('/businesses/check-slug', [BusinessController::class, 'checkSlug'])->name('businesses.check-slug');

    Route::get('/businesses/check-name', [BusinessController::class, 'checkName'])->name('businesses.check-name');
});
