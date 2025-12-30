<?php

use App\Http\Controllers\EmailCampaignController;
use Illuminate\Support\Facades\Route;

Route::get('ping', fn () => response()->json(['ok' => true]));

Route::post('/email-campaigns', [EmailCampaignController::class, 'store']);
Route::get('/email-campaigns/{email_campaign}/graph', [EmailCampaignController::class, 'getGraph']);
Route::put('/email-campaigns/{email_campaign}/graph', [EmailCampaignController::class, 'updateGraph']);

