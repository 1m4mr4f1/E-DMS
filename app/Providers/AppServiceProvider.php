<?php

namespace App\Providers;

use App\Models\Document;
use App\Policies\DocumentPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        Gate::policy(Document::class, DocumentPolicy::class);

        Gate::before(function ($user, $ability) {
            return $user->role_id == 1 ? true : null;
        });

        // Compose header view with notification data (keeps view logic-free)
        View::composer('components.header', function ($view) {
            $user = Auth::user();
            if (!$user) {
                $view->with(['unreadCount' => 0, 'allNotifications' => collect()]);
                return;
            }

            $userId = $user->id;

            $unreadCount = DB::table('notifications')
                ->where('user_id', $userId)
                ->where('is_read', false)
                ->count();

            $allNotifications = DB::table('notifications')
                ->where('user_id', $userId)
                ->orderByDesc('created_at')
                ->limit(5)
                ->get();

            $view->with(compact('unreadCount', 'allNotifications'));
        });
    }
}