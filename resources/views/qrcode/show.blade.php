@extends('layouts.app')
@section('title', 'Profile QR Code')

@section('content')

<div class="topbar animate-fade-up">
    <div class="topbar-title">
        <h1>Profile <span class="text-gradient">QR Code</span></h1>
        <p>Share your personal Linkora QR code on print, badges, or screen.</p>
    </div>
    <div class="topbar-actions">
        <a href="{{ route('profile.show', $user->username) }}" target="_blank" class="btn btn-secondary btn-sm">
            <i class="fas fa-eye"></i> View Profile
        </a>
    </div>
</div>

<div style="display:grid; grid-template-columns:1fr 340px; gap:1.5rem; align-items:start;">

    {{-- Main QR Display Card --}}
    <div class="card animate-fade-up" style="animation-delay:.05s; display:flex; flex-direction:column; align-items:center; text-align:center; padding:3rem 2rem;">

        <div style="padding:1.5rem; background:rgba(255,255,255,0.02); border:1px solid var(--border); border-radius:var(--radius-lg); box-shadow:var(--shadow-purple); margin-bottom:1.5rem;" id="qrSvgWrapper">
            {!! $qrCodeSvg !!}
        </div>

        <h3 style="margin-bottom:0.25rem;">&#64;{{ $user->username }}</h3>
        <p style="font-size:0.85rem; color:var(--text-muted); margin-bottom:1.5rem;">
            Scan to view {{ $user->name }}'s profile and active links
        </p>

        <div style="display:flex; gap:0.75rem; flex-wrap:wrap; justify-content:center;">
            <a href="{{ route('qrcode.download') }}" class="btn btn-primary">
                <i class="fas fa-download"></i> Download Vector SVG
            </a>
            <button type="button" class="btn btn-secondary" onclick="copyToClipboard('{{ $profileUrl }}', this)">
                <i class="fas fa-copy"></i> Copy Profile Link
            </button>
        </div>
    </div>

    {{-- Helpful instructions sidebar --}}
    <div style="display:flex; flex-direction:column; gap:1.25rem;">
        <div class="card card-purple animate-fade-up" style="animation-delay:.1s;">
            <h4 style="margin-bottom:0.75rem;"><i class="fas fa-lightbulb" style="color:var(--accent);"></i> Pro Tips</h4>
            <ul style="padding-left:1.2rem; font-size:0.85rem; color:var(--text-secondary); line-height:1.7;">
                <li>Print this on your business cards, banners, or stickers.</li>
                <li>Add it as a presentation slide ending or poster badge.</li>
                <li>SVGs scale infinitely without losing crisp sharpness!</li>
            </ul>
        </div>

        <div class="card animate-fade-up" style="animation-delay:.15s;">
            <h4 style="margin-bottom:0.5rem;">Target URL</h4>
            <div style="word-break:break-all; font-size:0.8rem; color:var(--accent); background:var(--bg-input); padding:0.75rem; border-radius:var(--radius); border:1px solid var(--border);">
                {{ $profileUrl }}
            </div>
        </div>
    </div>

</div>

@endsection
