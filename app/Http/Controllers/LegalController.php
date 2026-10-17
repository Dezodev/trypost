<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class LegalController extends Controller
{
    public function terms(): Response
    {
        return Inertia::render('legal/Terms', [
            'contactEmail' => config('mail.from.address'),
        ]);
    }

    public function privacy(): Response
    {
        return Inertia::render('legal/Privacy', [
            'contactEmail' => config('mail.from.address'),
        ]);
    }
}
