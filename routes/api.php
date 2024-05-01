<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});




Route::group(['prefix' => 'v1'],function (){


    // =================== Auth Controller ======================= //
    Route::group(['prefix' => 'user'],function(){
        Route::post('register',[\App\Http\Controllers\auth\AuthController::class,'register']);
        Route::post('login',[\App\Http\Controllers\auth\AuthController::class,'login'])->name('login');
        Route::post('logout',[\App\Http\Controllers\auth\AuthController::class,'logout']);
    });



    // =================== Category Controller ========================== //
    Route::group(['prefix' => 'category'],function (){
       Route::get('index',[\App\Http\Controllers\CategoryController::class,'index']);
       Route::post('create',[\App\Http\Controllers\CategoryController::class,'store'])->middleware(['auth:sanctum','isAdmin']);
       Route::delete('delete/{id}',[\App\Http\Controllers\CategoryController::class,'delete']);
       Route::get('find/{id}',[\App\Http\Controllers\CategoryController::class,'find']);
    });


    // =================== Ad Controller =============================== //
    Route::group(['prefix' => 'ad'],function(){
     Route::post('create',[\App\Http\Controllers\AdvertisingController::class,'store'])->middleware(['auth:sanctum']);
     Route::get('index',[\App\Http\Controllers\AdvertisingController::class,'index']);
     Route::delete('/admin/delete/{id}',[\App\Http\Controllers\AdvertisingController::class,'adminDelete'])->middleware(['auth:sanctum','isAdmin']);
     Route::get('/find',[\App\Http\Controllers\AdvertisingController::class,'find'])->middleware(['auth:sanctum']);
     Route::delete('/delete/{id}',[\App\Http\Controllers\AdvertisingController::class,'delete'])->middleware(['auth:sanctum']);
     Route::get('/search',[\App\Http\Controllers\AdvertisingController::class,'search']);
    });
});



