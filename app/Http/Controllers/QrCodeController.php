<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeController extends Controller
{
    /**
     * Show QR code page with preview and download options.
     */
    public function show()
    {
        $user = Auth::user();

        if (!$user->username) {
            return redirect()->route('profile.edit')
                ->with('error', 'Please set your username first to generate a profile QR code.');
        }

        $profileUrl = route('profile.show', $user->username);

        // Generate SVG string using simple-qrcode
        $qrCodeSvg = QrCode::size(260)
            ->color(139, 92, 246)
            ->backgroundColor(10, 10, 15)
            ->margin(2)
            ->generate($profileUrl);

        return view('qrcode.show', compact('user', 'profileUrl', 'qrCodeSvg'));
    }

    /**
     * Download QR code as SVG image.
     */
    public function download()
    {
        $user = Auth::user();

        if (!$user->username) {
            return redirect()->route('profile.edit');
        }

        $profileUrl = route('profile.show', $user->username);

        $svg = QrCode::format('svg')
            ->size(500)
            ->color(139, 92, 246)
            ->backgroundColor(10, 10, 15)
            ->margin(2)
            ->generate($profileUrl);

        return response($svg, 200, [
            'Content-Type'        => 'image/svg+xml',
            'Content-Disposition' => 'attachment; filename="linkora-qr-' . $user->username . '.svg"',
        ]);
    }
}
