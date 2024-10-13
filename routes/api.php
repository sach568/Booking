<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\BookingController;
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

// Route to retrieve the authenticated user
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route to apply a discount
Route::post('/apply-discount', [DiscountController::class, 'applyDiscount']);
Route::post('/family-member-discount', [DiscountController::class, 'familyMemberDiscount']);
Route::post('/recurring-discount', [DiscountController::class, 'recurringDiscount']);
