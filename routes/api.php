<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataController;
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


Route::group(['middleware' => 'api','prefix' => 'auth'], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/refreshtoken', [AuthController::class, 'refreshtoken']);
});



Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('getRekAdmin',[DataController::class, 'getRekAdmin']);
    Route::get('getBank',[DataController::class, 'getBank']);
    Route::get('getUserInfo',[AuthController::class, 'getUserInfo']);
    Route::post('createTransfer',[DataController::class, 'createTransfer']);
    Route::get('getListTransfer',[DataController::class, 'getListTransfer']);

});
