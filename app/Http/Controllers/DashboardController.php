<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $documentQuery = Document::query();

        if ($user->role_id != 1) {
            $documentQuery->where(function ($query) use ($user) {
                $query->where('visibility', 'public')
                    ->orWhere('division_id', $user->division_id);
            });
        }

        $allowedDocumentIds = (clone $documentQuery)->pluck('id');

        $stats = [
            'totalDocuments' => (clone $documentQuery)->count(),
            'newDocumentsThisMonth' => (clone $documentQuery)->where('created_at', '>=', Carbon::now()->startOfMonth())->count(),
            'expiringSharedDocuments' => 0
        ];

        $activities = DB::table('document_activity_log as log')
            ->join('users as actor', 'actor.id', '=', 'log.actor_id')
            ->join('documents as doc', 'doc.id', '=', 'log.document_id')
            ->whereIn('log.document_id', $allowedDocumentIds)
            ->select('log.*', 'actor.full_name as actor_name', 'doc.name as document_name')
            ->orderByDesc('log.created_at')
            ->limit(10)
            ->get();

        $recentActivities = [];
        foreach ($activities as $activity) {
            $recentActivities[] = [
                'actor' => $activity->actor_name,
                'document' => $activity->document_name,
                'action' => $activity->action_type,
                'timestamp' => Carbon::parse($activity->created_at)->diffForHumans()
            ];
        }

        $sharedWithMyDivision = [];
        $quickAccess = [];

        return view('dashboard.index', compact('stats', 'recentActivities', 'sharedWithMyDivision', 'quickAccess'));
    }
}