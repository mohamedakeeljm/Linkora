<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{
    /** Show the profile edit form */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /** Update the user's profile information */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'username' => [
                'required', 'string', 'min:3', 'max:30',
                'regex:/^[a-zA-Z0-9_]+$/',
                Rule::unique('users')->ignore($user->id),
            ],
            'email'    => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'bio'      => ['nullable', 'string', 'max:200'],
            'website'  => ['nullable', 'url', 'max:255'],
            'avatar'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:5120'],
        ], [
            'username.regex' => 'Username can only contain letters, numbers, and underscores.',
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $file = $request->file('avatar');

            // If GD or Imagick is enabled, resize with Intervention Image; otherwise store directly
            if (extension_loaded('gd') || extension_loaded('imagick')) {
                try {
                    $img = Image::make($file)
                        ->fit(200, 200)
                        ->encode('jpg', 85);

                    $path = 'avatars/' . $user->id . '_' . time() . '.jpg';
                    Storage::disk('public')->put($path, (string) $img);
                } catch (\Exception $e) {
                    $path = $file->store('avatars', 'public');
                }
            } else {
                $path = $file->store('avatars', 'public');
            }

            $validated['avatar'] = $path;
        }

        // Handle email change
        if ($user->email !== $validated['email']) {
            $user->email_verified_at = null;
        }

        $user->fill($validated);
        $user->save();

        return Redirect::route('profile.edit')
            ->with('success', 'Profile updated successfully!');
    }

    /** Delete the user's account */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        // Clean up avatar
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
