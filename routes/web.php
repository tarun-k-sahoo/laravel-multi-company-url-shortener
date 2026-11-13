<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\WebAuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\ShortUrlController;


// Authentication Routes


Route::middleware('guest')->group(function () {

    Route::get('/', [WebAuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [WebAuthController::class, 'login'])
        ->name('login.submit');

    // Accept invitation.
    Route::get('/invitations/accept/{token}', [
        InvitationController::class,
        'showAcceptForm'
    ])->name('invitations.accept');

    Route::post('/invitations/accept/{token}', [
        InvitationController::class,
        'accept'
    ])->name('invitations.accept.submit');
    Route::get('/s/{shortCode}', [ShortUrlController::class, 'redirectToOriginal'])
        ->name('short-urls.redirect');

});



// Temporary Permission Testing Route


Route::middleware(['auth', 'permission:company.create'])
    ->get('/test-company-permission', function () {
        return 'You have company.create permission!';
    })
    ->name('test.company.permission');



// Authenticated Routes


Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');


    // Logout
    Route::post('/logout', [WebAuthController::class, 'logout'])
        ->name('logout');



    // Company Management


    Route::middleware('permission:company.view')->group(function () {

        Route::get('/companies', [CompanyController::class, 'index'])
            ->name('companies.index');

    });

    Route::middleware('permission:company.create')->group(function () {

        Route::get('/companies/create', [CompanyController::class, 'create'])
            ->name('companies.create');

        Route::post('/companies', [CompanyController::class, 'store'])
            ->name('companies.store');

    });

    Route::middleware('permission:company.update')->group(function () {

        Route::patch('/companies/{company}/status', [CompanyController::class, 'toggleStatus'])
            ->name('companies.toggle-status');

    });

    // Invitation routes.
    Route::middleware('permission:user.invite')->group(function () {


        Route::get('/invitations/create', [
            InvitationController::class,
            'create'
        ])->name('invitations.create');


        Route::post('/invitations', [
            InvitationController::class,
            'store'
        ])->name('invitations.store');


        Route::patch('/invitations/{invitation}/cancel', [
            InvitationController::class,
            'cancel'
        ])->name('invitations.cancel');

    });


    Route::get('/invitations', [
        InvitationController::class,
        'index'
    ])->middleware('permission:user.view')
        ->name('invitations.index');

    Route::middleware('permission:short_url.view')->group(function () {

        Route::get('/short-urls', [ShortUrlController::class, 'index'])
            ->name('short-urls.index');

    });

    Route::middleware('permission:short_url.create')->group(function () {

        Route::get('/short-urls/create', [ShortUrlController::class, 'create'])
            ->name('short-urls.create');

        Route::post('/short-urls', [ShortUrlController::class, 'store'])
            ->name('short-urls.store');

    });

});
