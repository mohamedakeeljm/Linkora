<?php

namespace App\Http\Controllers;

use App\Models\SocialLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SocialLinkController extends Controller
{
    /**
     * Display a listing of the user's social links.
     */
    public function index()
    {
        $links = Auth::user()->socialLinks()->orderBy('sort_order', 'asc')->get();
        $platforms = SocialLink::supportedPlatforms();

        return view('links.index', compact('links', 'platforms'));
    }

    /**
     * Show the form for creating a new social link.
     */
    public function create()
    {
        $platforms = SocialLink::supportedPlatforms();
        return view('links.create', compact('platforms'));
    }

    /**
     * Store a newly created social link in storage.
     */
    public function store(Request $request)
    {
        $platforms = array_keys(SocialLink::supportedPlatforms());

        $validated = $request->validate([
            'platform' => ['required', 'string', 'in:' . implode(',', $platforms)],
            'label'    => ['required', 'string', 'max:100'],
            'url'      => ['required', 'string', 'max:2048'],
        ]);

        // Auto-format into complete valid destination (e.g. mailto: or https://wa.me/ or https://instagram.com/user)
        $formattedUrl = SocialLink::formatUrl($validated['platform'], $validated['url']);

        $maxOrder = Auth::user()->socialLinks()->max('sort_order') ?? 0;

        Auth::user()->socialLinks()->create([
            'platform'   => $validated['platform'],
            'label'      => $validated['label'],
            'url'        => $formattedUrl,
            'sort_order' => $maxOrder + 1,
            'is_active'  => true,
        ]);

        return redirect()->route('links.index')->with('success', 'Social link added successfully!');
    }

    /**
     * Show the form for editing the specified social link.
     */
    public function edit(SocialLink $link)
    {
        abort_if($link->user_id !== Auth::id(), 403);

        $platforms = SocialLink::supportedPlatforms();
        return view('links.edit', compact('link', 'platforms'));
    }

    /**
     * Update the specified social link in storage.
     */
    public function update(Request $request, SocialLink $link)
    {
        abort_if($link->user_id !== Auth::id(), 403);

        $platforms = array_keys(SocialLink::supportedPlatforms());

        $validated = $request->validate([
            'platform' => ['required', 'string', 'in:' . implode(',', $platforms)],
            'label'    => ['required', 'string', 'max:100'],
            'url'      => ['required', 'string', 'max:2048'],
        ]);

        // Auto-format url
        $validated['url'] = SocialLink::formatUrl($validated['platform'], $validated['url']);

        $link->update($validated);

        return redirect()->route('links.index')->with('success', 'Social link updated successfully!');
    }

    /**
     * Remove the specified social link from storage.
     */
    public function destroy(SocialLink $link)
    {
        abort_if($link->user_id !== Auth::id(), 403);

        $link->delete();

        return redirect()->route('links.index')->with('success', 'Social link deleted successfully!');
    }

    /**
     * Toggle the link's active status.
     */
    public function toggle(SocialLink $link)
    {
        abort_if($link->user_id !== Auth::id(), 403);

        $link->update([
            'is_active' => !$link->is_active,
        ]);

        return response()->json([
            'success'   => true,
            'is_active' => $link->is_active,
            'message'   => $link->is_active ? 'Link activated' : 'Link hidden',
        ]);
    }

    /**
     * Update the sort order of social links.
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'order'   => ['required', 'array'],
            'order.*' => ['integer'],
        ]);

        $order = $request->input('order');
        $userId = Auth::id();

        foreach ($order as $index => $id) {
            SocialLink::where('id', $id)
                ->where('user_id', $userId)
                ->update(['sort_order' => $index + 1]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Order updated successfully',
        ]);
    }
}
