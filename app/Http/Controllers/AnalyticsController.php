<?php

namespace App\Http\Controllers;

use App\Models\AnalyticsEvent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Display detailed analytics for views and clicks.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $days = (int) $request->get('days', 30);
        if (!in_array($days, [7, 14, 30, 90])) {
            $days = 30;
        }

        $startDate = Carbon::today()->subDays($days - 1);

        // Overall Totals
        $totalViews = $user->analyticsEvents()->where('type', 'view')->count();
        $totalClicks = $user->analyticsEvents()->where('type', 'click')->count();
        $overallCtr = $totalViews > 0 ? round(($totalClicks / $totalViews) * 100, 1) : 0;

        // Filtered range totals
        $rangeViews = $user->analyticsEvents()
            ->where('type', 'view')
            ->where('created_at', '>=', $startDate)
            ->count();

        $rangeClicks = $user->analyticsEvents()
            ->where('type', 'click')
            ->where('created_at', '>=', $startDate)
            ->count();

        // Daily trend data for chart
        $labels = [];
        $viewsData = [];
        $clicksData = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $labels[] = $date->format($days > 14 ? 'M d' : 'D, M d');

            $viewsData[] = $user->analyticsEvents()
                ->where('type', 'view')
                ->whereDate('created_at', $date)
                ->count();

            $clicksData[] = $user->analyticsEvents()
                ->where('type', 'click')
                ->whereDate('created_at', $date)
                ->count();
        }

        // Link performance list
        $linksPerformance = $user->socialLinks()
            ->withCount(['analyticsEvents as range_clicks' => function ($q) use ($startDate) {
                $q->where('type', 'click')->where('created_at', '>=', $startDate);
            }])
            ->orderByDesc('range_clicks')
            ->get();

        // Top Referrers
        $topReferrers = $user->analyticsEvents()
            ->where('type', 'view')
            ->whereNotNull('referer')
            ->where('referer', '!=', '')
            ->where('created_at', '>=', $startDate)
            ->select('referer', DB::raw('count(*) as count'))
            ->groupBy('referer')
            ->orderByDesc('count')
            ->take(5)
            ->get();

        return view('analytics.index', compact(
            'days',
            'totalViews',
            'totalClicks',
            'overallCtr',
            'rangeViews',
            'rangeClicks',
            'labels',
            'viewsData',
            'clicksData',
            'linksPerformance',
            'topReferrers'
        ));
    }
}
