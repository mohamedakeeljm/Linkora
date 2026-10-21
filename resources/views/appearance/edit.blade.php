@extends('layouts.app')
@section('title', 'Appearance Settings')

@section('content')

<div class="topbar animate-fade-up">
    <div class="topbar-title">
        <h1>Appearance & <span class="text-gradient">Theme</span></h1>
        <p>Customize the look and feel of your public Linkora page.</p>
    </div>
    @if(auth()->user()->username)
    <div class="topbar-actions">
        <a href="{{ route('profile.show', auth()->user()->username) }}" target="_blank" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-up-right-from-square"></i> Live Page
        </a>
    </div>
    @endif
</div>

<form action="{{ route('appearance.update') }}" method="POST" id="appearanceForm">
    @csrf
    @method('PATCH')

    <div style="display:grid; grid-template-columns:1fr 340px; gap:1.5rem; align-items:start;">

        {{-- Left Form Column --}}
        <div style="display:flex; flex-direction:column; gap:1.5rem;">

            {{-- Preset Themes --}}
            <div class="card animate-fade-up" style="animation-delay:.05s;">
                <h4 style="margin-bottom: 1.25rem;">Preset Themes</h4>
                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(130px, 1fr)); gap:1rem;">
                    @foreach($themes as $key => $info)
                        <label style="cursor:pointer; display:block;">
                            <input type="radio" name="theme" value="{{ $key }}" class="sr-only"
                                   {{ old('theme', $settings->theme) === $key ? 'checked' : '' }}
                                   onchange="updatePreviewTheme('{{ $key }}')">
                            <div class="theme-choice-card" id="theme-card-{{ $key }}"
                                 style="padding:1rem; border-radius:var(--radius); border:2px solid {{ old('theme', $settings->theme) === $key ? 'var(--accent)' : 'var(--border)' }}; background:{{ $info['preview'] }}; text-align:center; transition:var(--transition);">
                                <div style="font-size:0.85rem; font-weight:700; color:#fff;">{{ $info['name'] }}</div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Custom Colours --}}
            <div class="card animate-fade-up" style="animation-delay:.1s;">
                <h4 style="margin-bottom: 1.25rem;">Custom Color Overrides</h4>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem;">
                    <div class="form-group">
                        <label class="form-label" for="bg_color">Background Color (Hex)</label>
                        <div style="display:flex; gap:0.5rem; align-items:center;">
                            <input type="color" id="bg_picker" value="{{ old('bg_color', $settings->bg_color ?: '#0a0a0f') }}"
                                   style="width:40px; height:40px; border-radius:8px; border:none; cursor:pointer; background:none;"
                                   oninput="syncColorInput('bg_color', this.value)">
                            <input type="text" name="bg_color" id="bg_color" class="form-control"
                                   value="{{ old('bg_color', $settings->bg_color) }}" placeholder="#0a0a0f"
                                   oninput="syncPicker('bg_picker', this.value); updatePreviewBg(this.value);">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="accent_color">Accent Color (Hex)</label>
                        <div style="display:flex; gap:0.5rem; align-items:center;">
                            <input type="color" id="accent_picker" value="{{ old('accent_color', $settings->accent_color ?: '#8b5cf6') }}"
                                   style="width:40px; height:40px; border-radius:8px; border:none; cursor:pointer; background:none;"
                                   oninput="syncColorInput('accent_color', this.value)">
                            <input type="text" name="accent_color" id="accent_color" class="form-control"
                                   value="{{ old('accent_color', $settings->accent_color) }}" placeholder="#8b5cf6"
                                   oninput="syncPicker('accent_picker', this.value); updatePreviewAccent(this.value);">
                        </div>
                    </div>
                </div>
                <p class="form-hint">Leave blank to use the preset theme's default colors.</p>
            </div>

            {{-- Button Styles --}}
            <div class="card animate-fade-up" style="animation-delay:.15s;">
                <h4 style="margin-bottom: 1.25rem;">Button Style</h4>
                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(100px, 1fr)); gap:0.75rem;">
                    @foreach($buttonStyles as $style)
                        <label style="cursor:pointer;">
                            <input type="radio" name="button_style" value="{{ $style }}" class="sr-only"
                                   {{ old('button_style', $settings->button_style) === $style ? 'checked' : '' }}
                                   onchange="updatePreviewBtn('{{ $style }}')">
                            <div class="btn-style-box" id="btn-style-{{ $style }}"
                                 style="padding:0.75rem 0.5rem; border-radius:var(--radius); border:1px solid {{ old('button_style', $settings->button_style) === $style ? 'var(--accent)' : 'var(--border)' }}; text-align:center; font-size:0.85rem; font-weight:600; text-transform:capitalize; background:var(--bg-input);">
                                {{ $style }}
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Layout --}}
            <div class="card animate-fade-up" style="animation-delay:.2s;">
                <h4 style="margin-bottom: 1.25rem;">Page Alignment & Layout</h4>
                <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:1rem;">
                    @foreach($layouts as $l)
                        <label style="cursor:pointer;">
                            <input type="radio" name="layout" value="{{ $l }}" class="sr-only"
                                   {{ old('layout', $settings->layout) === $l ? 'checked' : '' }}
                                   onchange="updatePreviewLayout('{{ $l }}')">
                            <div class="layout-box" id="layout-{{ $l }}"
                                 style="padding:0.85rem; border-radius:var(--radius); border:1px solid {{ old('layout', $settings->layout) === $l ? 'var(--accent)' : 'var(--border)' }}; text-align:center; font-size:0.85rem; font-weight:600; text-transform:capitalize; background:var(--bg-input);">
                                <i class="fas {{ $l === 'centered' ? 'fa-align-center' : ($l === 'left' ? 'fa-align-left' : 'fa-compress') }}" style="margin-bottom:0.25rem; display:block;"></i>
                                {{ $l }}
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <div style="display:flex; gap:0.75rem;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check"></i> Save Appearance
                </button>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancel</a>
            </div>

        </div>

        {{-- Right Column: Live Mockup Preview --}}
        <div style="position:sticky; top:1.5rem;" class="animate-fade-up" style="animation-delay:.15s;">
            <div class="card" style="padding:1rem;">
                <div style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.1em; color:var(--text-muted); text-align:center; margin-bottom:1rem;">
                    Interactive Phone Mockup
                </div>

                {{-- Phone frame mockup --}}
                <div id="mockupScreen"
                     style="width:100%; border-radius:28px; padding:2rem 1rem; border:6px solid #232338; background:{{ $settings->bg_color ?: '#0a0a0f' }}; min-height:420px; display:flex; flex-direction:column; align-items:center; text-align:center; transition:all 0.3s ease;">

                    <img src="{{ auth()->user()->avatarUrl() }}" alt="avatar"
                         style="width:58px; height:58px; border-radius:50%; object-fit:cover; border:2px solid var(--accent); margin-bottom:0.6rem;">

                    <div style="font-weight:700; font-size:0.95rem; color:#fff;" id="mockupName">{{ auth()->user()->name }}</div>
                    <div style="font-size:0.75rem; color:var(--accent); margin-bottom:1.25rem;" id="mockupHandle">&#64;{{ auth()->user()->username ?? 'username' }}</div>

                    {{-- Sample Link Buttons --}}
                    <div id="mockupButtons" style="display:flex; flex-direction:column; gap:0.6rem; width:100%;">
                        <div class="mockup-btn" style="padding:0.65rem 1rem; font-size:0.78rem; font-weight:600; color:#fff; background:rgba(255,255,255,0.08); border:1px solid rgba(255,255,255,0.15); display:flex; justify-content:space-between; align-items:center; border-radius:9999px;">
                            <i class="fab fa-instagram" style="color:#E1306C;"></i>
                            <span>Follow on Instagram</span>
                            <i class="fas fa-arrow-right" style="font-size:0.65rem; opacity:0.5;"></i>
                        </div>
                        <div class="mockup-btn" style="padding:0.65rem 1rem; font-size:0.78rem; font-weight:600; color:#fff; background:rgba(255,255,255,0.08); border:1px solid rgba(255,255,255,0.15); display:flex; justify-content:space-between; align-items:center; border-radius:9999px;">
                            <i class="fab fa-youtube" style="color:#FF0000;"></i>
                            <span>Subscribe on YouTube</span>
                            <i class="fas fa-arrow-right" style="font-size:0.65rem; opacity:0.5;"></i>
                        </div>
                        <div class="mockup-btn" style="padding:0.65rem 1rem; font-size:0.78rem; font-weight:600; color:#fff; background:rgba(255,255,255,0.08); border:1px solid rgba(255,255,255,0.15); display:flex; justify-content:space-between; align-items:center; border-radius:9999px;">
                            <i class="fab fa-github"></i>
                            <span>GitHub Projects</span>
                            <i class="fas fa-arrow-right" style="font-size:0.65rem; opacity:0.5;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>

@endsection

@push('scripts')
<script>
const themeColors = {
    'default':  { bg: '#0a0a0f', accent: '#8b5cf6' },
    'ocean':    { bg: '#07111e', accent: '#0ea5e9' },
    'sunset':   { bg: '#1a0b12', accent: '#f43f5e' },
    'forest':   { bg: '#08140e', accent: '#10b981' },
    'midnight': { bg: '#030712', accent: '#6366f1' },
    'rose':     { bg: '#17060f', accent: '#ec4899' },
};

function updatePreviewTheme(themeKey) {
    document.querySelectorAll('.theme-choice-card').forEach(c => c.style.borderColor = 'var(--border)');
    const sel = document.getElementById('theme-card-' + themeKey);
    if (sel) sel.style.borderColor = 'var(--accent)';

    const t = themeColors[themeKey] || themeColors['default'];
    const screen = document.getElementById('mockupScreen');
    if (!document.getElementById('bg_color').value) {
        screen.style.background = t.bg;
    }
}

function syncColorInput(inputId, val) {
    document.getElementById(inputId).value = val;
    if (inputId === 'bg_color') updatePreviewBg(val);
    if (inputId === 'accent_color') updatePreviewAccent(val);
}

function syncPicker(pickerId, val) {
    if (/^#[0-9A-Fa-f]{6}$/.test(val)) {
        document.getElementById(pickerId).value = val;
    }
}

function updatePreviewBg(val) {
    const screen = document.getElementById('mockupScreen');
    screen.style.background = val || '#0a0a0f';
}

function updatePreviewAccent(val) {
    const color = val || 'var(--accent)';
    const handle = document.getElementById('mockupHandle');
    if (handle) handle.style.color = color;
}

function updatePreviewBtn(style) {
    document.querySelectorAll('.btn-style-box').forEach(b => b.style.borderColor = 'var(--border)');
    const active = document.getElementById('btn-style-' + style);
    if (active) active.style.borderColor = 'var(--accent)';

    document.querySelectorAll('.mockup-btn').forEach(btn => {
        if (style === 'pill') {
            btn.style.borderRadius = '9999px';
            btn.style.background = 'rgba(255,255,255,0.08)';
            btn.style.border = '1px solid rgba(255,255,255,0.15)';
        } else if (style === 'rounded') {
            btn.style.borderRadius = '10px';
            btn.style.background = 'rgba(255,255,255,0.08)';
            btn.style.border = '1px solid rgba(255,255,255,0.15)';
        } else if (style === 'sharp') {
            btn.style.borderRadius = '0px';
            btn.style.background = 'rgba(255,255,255,0.08)';
            btn.style.border = '1px solid rgba(255,255,255,0.15)';
        } else if (style === 'outline') {
            btn.style.borderRadius = '9999px';
            btn.style.background = 'transparent';
            btn.style.border = '2px solid var(--accent)';
        } else if (style === 'glass') {
            btn.style.borderRadius = '14px';
            btn.style.background = 'rgba(255,255,255,0.04)';
            btn.style.border = '1px solid rgba(255,255,255,0.1)';
        }
    });
}

function updatePreviewLayout(layout) {
    document.querySelectorAll('.layout-box').forEach(b => b.style.borderColor = 'var(--border)');
    const active = document.getElementById('layout-' + layout);
    if (active) active.style.borderColor = 'var(--accent)';

    const screen = document.getElementById('mockupScreen');
    if (layout === 'left') {
        screen.style.alignItems = 'flex-start';
        screen.style.textAlign = 'left';
    } else {
        screen.style.alignItems = 'center';
        screen.style.textAlign = 'center';
    }
}
</script>
@endpush
