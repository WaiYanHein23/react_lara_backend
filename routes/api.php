<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\TempImageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post("blogs",[BlogController::class,'store']);
Route::post("temp_image",[TempImageController::class,'store']);
Route::get("blogs",[BlogController::class,'index']);
Route::get("blogs/{id}",[BlogController::class,'show']);
Route::put("blogs/{id}",[BlogController::class,'update']);
Route::delete("blogs/{id}",[BlogController::class,'destroy']);


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
