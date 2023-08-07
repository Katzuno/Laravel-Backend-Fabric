<?php

use App\Http\Controllers\RecordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('records')->group(function () {
    Route::get('/', [RecordController::class, 'index']);
    Route::post('/', [RecordController::class, 'store']);
    Route::get('/search', [RecordController::class, 'search']);
    Route::get('/{record}', [RecordController::class, 'show']);
    Route::patch('/{record}', [RecordController::class, 'update']);
    Route::delete('/{record}', [RecordController::class, 'destroy']);
});
