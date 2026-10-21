<?php

namespace App\Http\Controllers;

use App\Models\ProfileSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppearanceController extends Controller
{
    /**
     * Show the appearance customization form.
     */
    public function edit()
    {
        $user = Auth::user();
        $settings = $user->getOrCreateSettings();
        $themes = ProfileSetting::themes();
        $buttonStyles = ProfileSetting::buttonStyles();
        $layouts = ProfileSetting::layouts();

        return view('appearance.edit', compact('user', 'settings', 'themes', 'buttonStyles', 'layouts'));
    }

    /**
     * Update the user's appearance settings.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $settings = $user->getOrCreateSettings();

        $validated = $request->validate([
            'theme'        => ['required', 'string', 'in:' . implode(',', array_keys(ProfileSetting::themes()))],
            'button_style' => ['required', 'string', 'in:' . implode(',', ProfileSetting::buttonStyles())],
            'layout'       => ['required', 'string', 'in:' . implode(',', ProfileSetting::layouts())],
            'bg_color'     => ['nullable', 'regex:/^#([a-fA-F0-9]{6})$/'],
            'accent_color' => ['nullable', 'regex:/^#([a-fA-F0-9]{6})$/'],
        ]);

        $settings->update($validated);

        return redirect()->route('appearance.edit')->with('success', 'Appearance updated successfully!');
    }
}
