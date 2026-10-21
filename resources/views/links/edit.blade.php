@extends('layouts.app')
@section('title', 'Edit Link')

@section('content')

<div class="topbar animate-fade-up">
    <div class="topbar-title">
        <h1>Edit <span class="text-gradient">Link</span></h1>
        <p>Update link label, platform icon, or destination address.</p>
    </div>
    <div class="topbar-actions">
        <a href="{{ route('links.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back to Links
        </a>
    </div>
</div>

<div class="card animate-fade-up" style="max-width: 640px; animation-delay:.05s;">
    <form action="{{ route('links.update', $link) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Platform select --}}
        <div class="form-group">
            <label class="form-label" for="platform">Platform <span style="color:var(--danger)">*</span></label>
            <select name="platform" id="platform" class="form-control @error('platform') is-invalid @enderror" required onchange="handlePlatformChange(this)">
                @foreach($platforms as $key => $info)
                    <option value="{{ $key }}"
                            data-name="{{ $info['name'] }}"
                            data-prefix="{{ $info['prefix'] }}"
                            data-placeholder="{{ $info['placeholder'] }}"
                            data-hint="{{ $info['hint'] }}"
                            data-type="{{ $info['type'] }}"
                            data-icon="{{ $info['icon'] }}"
                            data-color="{{ $info['color'] }}"
                            {{ old('platform', $link->platform) === $key ? 'selected' : '' }}>
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
                       value="{{ old('label', $link->label) }}" required maxlength="100">
            </div>
            @error('label') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        {{-- URL / Username Input --}}
        <div class="form-group">
            <label class="form-label" for="url">Destination / Username</label>
            <input type="text" name="url" id="url" class="form-control @error('url') is-invalid @enderror"
                   value="{{ old('url', $link->url) }}" required maxlength="2048">
            <p class="form-hint" id="urlHint">You can paste the full URL or enter the username/number.</p>
            @error('url') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div style="display:flex; gap:0.75rem; margin-top:1.5rem;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-check"></i> Update Link
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
    const icon = selected.getAttribute('data-icon');
    const color = selected.getAttribute('data-color');
    const hint = selected.getAttribute('data-hint');

    const labelIcon = document.getElementById('labelIcon');
    const urlHint = document.getElementById('urlHint');

    if (labelIcon && icon) {
        labelIcon.className = icon + ' input-group-icon';
        labelIcon.style.color = color || 'var(--accent)';
    }

    if (urlHint && hint) {
        urlHint.textContent = hint;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const platformSelect = document.getElementById('platform');
    if (platformSelect) {
        handlePlatformChange(platformSelect);
    }
});
</script>
@endpush
