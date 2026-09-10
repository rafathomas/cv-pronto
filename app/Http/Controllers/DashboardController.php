<?php

namespace App\Http\Controllers;

use App\Domain\AI\Services\AiCreditService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request, AiCreditService $credits): View
    {
        $resume = $request->user()->resumes()->latest()->first();

        return view('dashboard', [
            'resume' => $resume,
            'creditsBalance' => $credits->balance($request->user()),
        ]);
    }
}
