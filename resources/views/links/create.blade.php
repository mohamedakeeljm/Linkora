@extends('layouts.app')
@section('title', 'Add New Link')

@section('content')

<div class="topbar animate-fade-up">
    <div class="topbar-title">
        <h1>Add <span class="text-gradient">Link</span></h1>
        <p>Create a new link button for your Linkora profile.</p>
    </div>
    <div class="topbar-actions">
        <a href="{{ route('links.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back to Links
        </a>
    </div>
</div>

<div class="card animate-fade-up" style="max-width: 640px; animation-delay:.05s;">
    <form action="{{ route('links.store') }}" method="POST">
        @csrf

        {{-- Platform select --}}
        <div class="form-group">
            <label class="form-label" for="platform">Platform <span style="color:var(--danger)">*</span></label>
            <select name="platform" id="platform" class="form-control @error('platform') is-invalid @enderror" required onchange="handlePlatformChange(this)">
                <option value="" disabled selected>Select a platform...</option>
                @foreach($platforms as $key => $info)
                    <option value="{{ $key }}"
                            data-name="{{ $info['name'] }}"
                            data-prefix="{{ $info['prefix'] }}"
                            data-placeholder="{{ $info['placeholder'] }}"
                            data-hint="{{ $info['hint'] }}"
                            data-type="{{ $info['type'] }}"
                            data-icon="{{ $info['icon'] }}"
                            data-color="{{ $info['color'] }}"
                            {{ old('platform') === $key ? 'selected' : '' }}>
                        {{ $info['name'] }}
                    </option>
                @endforeach
            </select>
            @error('platform') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        {{-- Label --}}
        <div class="form-group">
            <label class="form-label" for="label">Button Label <span style="color:var(--danger)">*</span></label>
            <div class="input-group">
                <i class="fas fa-tag input-group-icon" id="labelIcon"></i>
                <input type="text" name="label" id="label" class="form-control @error('label') is-invalid @enderror"
                       value="{{ old('label') }}" placeholder="e.g. Follow me on Instagram" required maxlength="100">
            </div>
            <p class="form-hint">Text shown on your public profile link button.</p>
            @error('label') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        {{-- URL / Username Input with dynamic prefix --}}
        <div class="form-group">
            <label class="form-label" for="url" id="urlLabel">Username / Link <span style="color:var(--danger)">*</span></label>
            <div style="display:flex; align-items:stretch;">
                <span id="prefixBadge" style="display:none; align-items:center; padding:0 0.85rem; background:rgba(255,255,255,0.05); border:1px solid var(--border-input); border-right:none; border-radius:var(--radius) 0 0 var(--radius); color:var(--accent); font-size:0.85rem; font-weight:500; white-space:nowrap;">
                </span>
                <input type="text" name="url" id="url" class="form-control @error('url') is-invalid @enderror"
                       value="{{ old('url') }}" placeholder="Enter username or URL" required maxlength="2048"
                       style="border-radius:var(--radius);">
            </div>
            <p class="form-hint" id="urlHint">You can type either just your username, number, or paste the full URL.</p>
            @error('url') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div style="display:flex; gap:0.75rem; margin-top:1.5rem;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-plus"></i> Save Link
            </button>
            <a href="{{ route('links.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
function handlePlatformChange(select) {
    const selected = select.options[select.selectedIndex];
    const name = selected.getAttribute('data-name');
    const prefix = selected.getAttribute('data-prefix');
    const placeholder = selected.getAttribute('data-placeholder');
    const hint = selected.getAttribute('data-hint');
    const type = selected.getAttribute('data-type');
    const icon = selected.getAttribute('data-icon');
    const color = selected.getAttribute('data-color');

    const labelInput = document.getElementById('label');
    const labelIcon = document.getElementById('labelIcon');
    const urlInput = document.getElementById('url');
    const urlLabel = document.getElementById('urlLabel');
    const urlHint = document.getElementById('urlHint');
    const prefixBadge = document.getElementById('prefixBadge');

    // Auto-fill friendly label if empty or previously set to a default
    if (labelInput) {
        if (!labelInput.value || labelInput.dataset.auto) {
            labelInput.value = name;
            labelInput.dataset.auto = 'true';
        }
    }

    if (labelIcon && icon) {
        labelIcon.className = icon + ' input-group-icon';
        labelIcon.style.color = color || 'var(--accent)';
    }

    if (urlHint && hint) {
        urlHint.textContent = hint;
    }

    if (urlInput) {
        urlInput.placeholder = placeholder || 'Enter value';
        urlInput.type = (type === 'email' || type === 'tel') ? type : 'text';

        // Prefix badge configuration
        if (prefix && prefix !== 'mailto:' && prefix !== '') {
            prefixBadge.textContent = prefix;
            prefixBadge.style.display = 'flex';
            urlInput.style.borderRadius = '0 var(--radius) var(--radius) 0';
        } else if (prefix === 'mailto:') {
            prefixBadge.textContent = 'mailto:';
            prefixBadge.style.display = 'flex';
            urlInput.style.borderRadius = '0 var(--radius) var(--radius) 0';
        } else {
            prefixBadge.style.display = 'none';
            urlInput.style.borderRadius = 'var(--radius)';
        }
    }
}

// Trigger initial on load if pre-selected
document.addEventListener('DOMContentLoaded', () => {
    const platformSelect = document.getElementById('platform');
    if (platformSelect && platformSelect.value) {
        handlePlatformChange(platformSelect);
    }
});
</script>
@endpush
