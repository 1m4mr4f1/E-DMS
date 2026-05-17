<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(protected DashboardService $dashboardService)
    {
    }

    public function index(Request $request): View
    {
        $user = $request->user();

        $data = $this->dashboardService->getDashboardData($user);

        return view('dashboard.index', [
            'stats' => $data['stats'],
            'recentActivities' => $data['recentActivities'],
            'sharedWithMyDivision' => $data['sharedWithMyDivision'],
            'quickAccess' => $data['quickAccess'],
        ]);
    }
}