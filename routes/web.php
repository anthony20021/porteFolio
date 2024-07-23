<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes([
    'register' => false, 
    'reset' => false, 
    'verify' => false, 
  ]);
Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index']);
Route::get('/project/{id}', [App\Http\Controllers\PageController::class, 'indexProject']);
Route::get('/projects', [App\Http\Controllers\PageController::class, 'indexProjectAll']);
Route::get('/cv' , [App\Http\Controllers\PageController::class, 'indexCv']);
Route::get('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout']);
Route::post('/contact', [App\Http\Controllers\HomeController::class, 'contact']);


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'index']);
    Route::prefix('gestion')->group(function () {
        Route::post('/project/save', [App\Http\Controllers\AdminController::class, 'saveProject']);
        Route::put('/project/save', [App\Http\Controllers\AdminController::class, 'putProject']);
        Route::delete('/project', [App\Http\Controllers\AdminController::class, 'deleteProject']);
        Route::post('/code/save', [App\Http\Controllers\AdminController::class, 'saveCode']);
        Route::put('/code/save', [App\Http\Controllers\AdminController::class, 'putCode']);
        Route::delete('/code', [App\Http\Controllers\AdminController::class, 'deleteCode']);
        Route::post('/code/file', [App\Http\Controllers\AdminController::class, 'saveCodeFile']);
        Route::post('/experience/save', [App\Http\Controllers\AdminController::class, 'saveExperience']);
        Route::put('/experience/save', [App\Http\Controllers\AdminController::class, 'putExperience']);
        Route::delete('/experience', [App\Http\Controllers\AdminController::class, 'deleteExperience']);
        Route::post('cv/save', [App\Http\Controllers\AdminController::class, 'saveCv']);
        Route::put('cv/save', [App\Http\Controllers\AdminController::class, 'putCv']);
        Route::delete('cv', [App\Http\Controllers\AdminController::class, 'deleteCv']);
        Route::post('/cv/file', [App\Http\Controllers\AdminController::class, 'saveCvFile']);
        Route::post('/photo', [App\Http\Controllers\PhotoController::class, 'save']);
        Route::delete('/photo', [App\Http\Controllers\PhotoController::class, 'delete']);
        Route::post('/message', [App\Http\Controllers\SendMailController::class, 'sendEmail']);
        Route::delete('/message', [App\Http\Controllers\AdminController::class, 'deleteMessage']);
    });
});

