<?php

declare(strict_types=1);

use App\Http\Controllers\LegalController;
use Illuminate\Support\Facades\Route;

Route::get('terms', [LegalController::class, 'terms'])->name('legal.terms');
Route::get('privacy', [LegalController::class, 'privacy'])->name('legal.privacy');

require __DIR__.'/webhook.php';
require __DIR__.'/auth.php';
require __DIR__.'/app.php';
