<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

#method GET
route::get("/animals", [AnimalController::class, 'index']);

#method POST
route:: post("/animals", [AnimalController::class, 'store']);

#method PUT
route::put("/animals/{id}", [AnimalController::class, 'update']);

#method DELETE
route::delete("/animals/{id}", [AnimalController::class, 'destroy']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/patient', [PatientController::class, 'index']);
Route::get('/patient/{id}', [PatientController::class, 'show']);
Route::post('/patient', [PatientController::class, 'store']);
Route::put('/patient/{id}', [PatientController::class, 'update']);
Route::delete('/patient/{id}', [PatientController::class, 'destroy']);
Route::get('/patient/search/{name}', [PatientController::class, 'search']);
Route::get('/patient/status/positive', [PatientController::class, 'positive']);
Route::get('/patient/status/recovered', [PatientController::class, 'recovered']);
Route::get('/patient/status/dead', [PatientController::class, 'dead']);

#endoint Register dan login
route::post('/register', [AuthController::class, 'register']);
route::post('/login', [AuthController::class, 'login']);