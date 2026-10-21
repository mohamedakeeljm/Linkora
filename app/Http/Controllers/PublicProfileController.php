<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SocialLink;
use App\Models\AnalyticsEvent;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class PublicProfileController extends Controller
{
    /** Display a user's public profile page */
    public function show(Request $request, string $username)
    {
        $user = User::where('username', $username)->firstOrFail();
        $settings = $user->getOrCreateSettings();
        $links    = $user->activeLinks;

        // Record profile view (fire-and-forget, don't block the response)
        try {
            AnalyticsEvent::recordView(
                $user,
                $request->ip(),
                $request->header('referer'),
                $request->header('User-Agent')
            );
        } catch (\Exception $e) {
            // Never fail the page load due to analytics
        }

        return view('public.profile', compact('user', 'settings', 'links'));
    }

    /** Track a link click then redirect to the destination */
    public function click(Request $request, string $username, SocialLink $link): RedirectResponse
    {
        // Verify this link belongs to the given user
        $user = User::where('username', $username)->firstOrFail();
        abort_if($link->user_id !== $user->id, 404);

        // Record the click
        try {
            $link->incrementClicks();
            AnalyticsEvent::recordClick($user, $link, $request->ip(), $request->header('User-Agent'));
        } catch (\Exception $e) {
            // Don't block redirect on analytics error
        }

        return redirect()->away($link->url);
    }
}
