<?php

use Illuminate\Support\Facades\Route; 
use App\Http\Controllers\DepartController;
use Illuminate\Http\Request; 
use App\Http\Controllers\DepartConfidentielController; 
use App\Http\Controllers\DepartMinisterielleController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/depart', [DepartController::class, 'index'])->name('depart.index');
Route::post('/depart', [DepartController::class, 'store'])->name('depart.store');
Route::get('/depart/{id}/edit', [DepartController::class, 'edit'])->name('depart.edit');
Route::put('/depart/{id}', [DepartController::class, 'update'])->name('depart.update'); // ✅ route PUT correcte
Route::delete('/depart/{depart}', [DepartController::class, 'destroy'])->name('depart.destroy');

// Routes pour les documents d'arrivée
Route::resource('arrivee', App\Http\Controllers\ArriveeController::class);

// Page d'accueil
Route::get('/', function () {
    return view('welcome');
});

// Authentification simple
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $username = $request->input('username');
    $password = $request->input('password');

    if ($username === 'DAI' && $password === 'root') {
        session(['authenticated' => true]);
        return redirect()->route('depart.index');
    }

    return back()->withErrors(['identifiants' => 'Nom d’utilisateur ou mot de passe incorrect.']);
});

Route::get('/logout', function () {
    session()->flush();
    return redirect()->route('login');
})->name('logout');

Route::resource('visa', App\Http\Controllers\VisaController::class);

Route::get('/depart-confidentiel', [DepartConfidentielController::class, 'index'])->name('departconfidentiel.index');
Route::post('/depart-confidentiel', [DepartConfidentielController::class, 'store'])->name('departconfidentiel.store');
Route::get('/depart-confidentiel/{id}/edit', [DepartConfidentielController::class, 'edit'])->name('departconfidentiel.edit');
Route::put('/depart-confidentiel/{id}', [DepartConfidentielController::class, 'update'])->name('departconfidentiel.update');
Route::delete('/depart-confidentiel/{id}', [DepartConfidentielController::class, 'destroy'])->name('departconfidentiel.destroy');
Route::resource('departministerielle', DepartMinisterielleController::class);



