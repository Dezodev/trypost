<?php

declare(strict_types=1);

use App\Http\Controllers\LegalController;
use App\Http\Controllers\Webhooks\MetaWebhookController;
use Illuminate\Support\Facades\Route;

Route::get('terms', [LegalController::class, 'terms'])->name('legal.terms');
Route::get('privacy', [LegalController::class, 'privacy'])->name('legal.privacy');
Route::get('data-deletion/{code}', [MetaWebhookController::class, 'dataDeletionStatus'])->name('legal.data-deletion.status');

require __DIR__.'/webhook.php';
require __DIR__.'/auth.php';
require __DIR__.'/app.php';
