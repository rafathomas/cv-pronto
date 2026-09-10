<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $resume = $request->user()->resumes()->latest()->first();

        return view('dashboard', [
            'resume' => $resume,
        ]);
    }
}
