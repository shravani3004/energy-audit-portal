<?php

use App\Http\Controllers\Api\AuditController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Energy Audit API Routes
|--------------------------------------------------------------------------
| Base URL in production: https://your-app.example.com/api
| Mirrors the fetch() call in the frontend's index.html (API_BASE_URL).
*/

Route::prefix('audits')->group(function () {
    Route::post('/', [AuditController::class, 'store']);
    Route::get('/{auditReport}', [AuditController::class, 'show']);
    Route::post('/{auditReport}/resend-email', [AuditController::class, 'resendEmail']);
});
