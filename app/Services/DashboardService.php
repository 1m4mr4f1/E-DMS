<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getDashboardData(User $user): array
    {
        return [
            'stats' => $this->getStatCards($user),
            'recentActivities' => $this->getRecentActivity($user),
            'sharedWithMyDivision' => $this->getSharedWithMyDivision($user),
            'quickAccess' => $this->getQuickAccess($user),
        ];
    }

    public function getStatCards(User $user): array
    {
        $accessibleDocuments = $this->getAccessibleDocumentsQuery($user->division_id);

        return [
            'totalDocuments' => $accessibleDocuments->count(),
            'newDocumentsThisMonth' => $accessibleDocuments->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->count(),
            'expiringSharedDocuments' => $this->getExpiringSharedDocumentsCount($user->division_id),
        ];
    }

    public function getRecentActivity(User $user): array
    {
        $query = DB::table('document_activity_log as activity')
            ->join('documents', 'documents.id', 'activity.document_id')
            ->join('users as actor', 'actor.id', 'activity.actor_id')
            ->whereNull('documents.deleted_at');

        // FIX SECURITY HOLE: Cegah User mengintip riwayat log aktivitas dokumen rahasia divisi lain
        if ($user->role_id != 1) { // Jika bukan Super Admin, filter ketat aksesnya
            $query->where(function ($q) use ($user) {
                $q->where('documents.division_id', $user->division_id)
                  ->orWhere('documents.visibility', 'public')
                  ->orWhereExists(function ($sub) use ($user) {
                      $sub->select(DB::raw(1))
                          ->from('document_sharing')
                          ->whereColumn('document_sharing.document_id', 'documents.id')
                          ->where('document_sharing.shared_to_division_id', $user->division_id)
                          ->where('document_sharing.is_active', true)
                          ->where(function ($expiry) {
                              $expiry->whereNull('expires_at')->orWhere('expires_at', '>', now());
                          });
                  });
            });
        }

        return $query->select([
                'activity.id',
                'activity.action_type',
                'activity.created_at',
                'documents.name as document_name',
                'actor.full_name as actor_name',
            ])
            ->orderBy('activity.created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($row) {
                return [
                    'actor' => $row->actor_name,
                    'document' => $row->document_name,
                    'action' => $this->formatActionLabel($row->action_type),
                    'timestamp' => Carbon::parse($row->created_at)->diffForHumans(),
                ];
            })
            ->toArray();
    }

    public function getSharedWithMyDivision(User $user): array
    {
        return DB::table('document_sharing as share')
            ->join('documents', 'documents.id', 'share.document_id')
            ->join('divisions as owner_division', 'owner_division.id', 'documents.division_id')
            ->join('users as sharer', 'sharer.id', 'share.shared_by')
            ->whereNull('documents.deleted_at')
            ->where('share.shared_to_division_id', $user->division_id)
            ->where('share.is_active', true)
            ->where('documents.division_id', '!=', $user->division_id)
            ->select([
                'documents.id as document_id',
                'documents.name as document_name',
                'owner_division.name as owner_division',
                'sharer.full_name as shared_by',
                'share.permission',
                'share.expires_at',
            ])
            ->orderBy('share.created_at', 'desc')
            ->limit(6)
            ->get()
            ->map(function ($row) {
                return [
                    'document_id' => $row->document_id,
                    'document_name' => $row->document_name,
                    'owner_division' => $row->owner_division,
                    'shared_by' => $row->shared_by,
                    'permission' => ucfirst($row->permission),
                    'expires_at' => $row->expires_at ? Carbon::parse($row->expires_at)->format('d M Y') : 'Tidak ada',
                ];
            })
            ->toArray();
    }

    public function getQuickAccess(User $user): array
    {
        return DB::table('document_activity_log as activity')
            ->join('documents', 'documents.id', 'activity.document_id')
            ->whereNull('documents.deleted_at')
            ->where('activity.actor_id', $user->id)
            ->whereIn('activity.action_type', ['view', 'download'])
            ->select([
                'activity.document_id',
                'documents.name as document_name',
                DB::raw('count(*) as access_count'),
                DB::raw('max(activity.created_at) as last_accessed_at'),
                DB::raw("max(case when activity.action_type = 'download' then 'Download' else 'View' end) as last_action"),
            ])
            ->groupBy('activity.document_id', 'documents.name')
            ->orderByDesc('access_count')
            ->orderByDesc('last_accessed_at')
            ->limit(6)
            ->get()
            ->map(function ($row) {
                return [
                    'document_name' => $row->document_name,
                    'access_count' => $row->access_count,
                    'last_accessed_at' => Carbon::parse($row->last_accessed_at)->diffForHumans(),
                    'last_action' => $row->last_action,
                ];
            })
            ->toArray();
    }

    protected function getAccessibleDocumentsQuery(int $divisionId)
    {
        return DB::table('documents')
            ->whereNull('documents.deleted_at')
            ->where(function ($query) use ($divisionId) {
                $query->where('division_id', $divisionId)
                    ->orWhere('visibility', 'public')
                    ->orWhereExists(function ($sub) use ($divisionId) {
                        $sub->select(DB::raw(1))
                            ->from('document_sharing')
                            ->whereColumn('document_sharing.document_id', 'documents.id')
                            ->where('document_sharing.shared_to_division_id', $divisionId)
                            ->where('document_sharing.is_active', true)
                            ->where(function ($expiry) {
                                $expiry->whereNull('expires_at')->orWhere('expires_at', '>', now());
                            });
                    });
            });
    }

    protected function getExpiringSharedDocumentsCount(int $divisionId): int
    {
        return DB::table('document_sharing as share')
            ->join('documents', 'documents.id', 'share.document_id')
            ->whereNull('documents.deleted_at')
            ->where('share.shared_to_division_id', $divisionId)
            ->where('share.is_active', true)
            ->whereNotNull('share.expires_at')
            ->whereBetween('share.expires_at', [Carbon::now(), Carbon::now()->addDays(7)])
            ->distinct()
            ->count('share.document_id');
    }

    protected function formatActionLabel(string $action): string
    {
        return match (strtolower($action)) {
            'upload', 'created' => 'Uploaded',
            'edit', 'edited', 'updated' => 'Edited',
            'download' => 'Downloaded',
            'view' => 'Viewed',
            default => ucfirst($action),
        };
    }
}