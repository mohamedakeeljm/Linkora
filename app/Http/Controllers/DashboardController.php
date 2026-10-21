<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AnalyticsEvent;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load(['socialLinks', 'activeLinks']);

        // ── Totals ────────────────────────────────────────────────
        $totalViews  = $user->analyticsEvents()->where('type', 'view')->count();
        $totalClicks = $user->analyticsEvents()->where('type', 'click')->count();
        $totalLinksAll = $user->socialLinks()->count();
        $totalLinks    = $user->activeLinks()->count();

        // ── Today's counts ────────────────────────────────────────
        $todayStart  = Carbon::today();
        $viewsToday  = $user->analyticsEvents()->where('type', 'view')->whereDate('created_at', $todayStart)->count();
        $clicksToday = $user->analyticsEvents()->where('type', 'click')->whereDate('created_at', $todayStart)->count();

        // ── Click-through rate ────────────────────────────────────
        $ctr = $totalViews > 0 ? round(($totalClicks / $totalViews) * 100, 1) : 0;

        // ── Top 5 links by click count ────────────────────────────
        $topLinks = $user->socialLinks()
            ->orderByDesc('click_count')
            ->take(5)
            ->get();

        // ── 7-day views chart data ────────────────────────────────
        $chartLabels = [];
        $chartData   = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = Carbon::today()->subDays($i);
            $chartLabels[] = $day->format('M d');
            $chartData[]   = $user->analyticsEvents()
                ->where('type', 'view')
                ->whereDate('created_at', $day)
                ->count();
        }

        return view('dashboard', compact(
            'totalViews',
            'totalClicks',
            'totalLinksAll',
            'totalLinks',
            'viewsToday',
            'clicksToday',
            'ctr',
            'topLinks',
            'chartLabels',
            'chartData'
        ));
    }
}
