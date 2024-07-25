<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::post('/initialize-payment', [PaymentController::class, 'initializePayment']);
Route::post('/verify-transaction', [PaymentController::class, 'verifyTransaction']);
Route::get('/transactions', [PaymentController::class, 'listTransactions']);
