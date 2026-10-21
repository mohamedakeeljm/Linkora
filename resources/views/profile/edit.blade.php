@extends('layouts.app')
@section('title', 'Edit Profile')

@section('content')

<div class="topbar animate-fade-up">
    <div class="topbar-title">
        <h1>Edit <span class="text-gradient">Profile</span></h1>
        <p>Update your public profile information and avatar.</p>
    </div>
    @if($user->username)
    <div class="topbar-actions">
        <a href="{{ route('profile.show', $user->username) }}" target="_blank" class="btn btn-secondary btn-sm">
            <i class="fas fa-eye"></i> Preview
        </a>
    </div>
    @endif
</div>

<form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
    @csrf @method('PATCH')

    <div style="display:grid;grid-template-columns:1fr 300px;gap:1.5rem;align-items:start;">

        {{-- Left: fields --}}
        <div style="display:flex;flex-direction:column;gap:1.25rem;">

            {{-- Avatar --}}
            <div class="card animate-fade-up" style="animation-delay:.05s;">
                <h4 style="margin-bottom:1.25rem;">Profile Photo</h4>
                <div style="display:flex;align-items:center;gap:1.5rem;">
                    <label for="avatarInput" class="avatar-upload" style="cursor:pointer;" title="Click to upload profile photo">
                        <img src="{{ $user->avatarUrl() }}" alt="Avatar" id="avatarPreview">
                        <span class="avatar-upload-overlay">
                            <i class="fas fa-camera"></i>
                        </span>
                        <span class="avatar-edit-badge">
                            <i class="fas fa-pencil-alt"></i>
                        </span>
                    </label>
                    <input type="file" name="avatar" id="avatarInput" accept="image/*"
                           style="display:none;" onchange="previewAvatar(this)">
                    <div>
                        <label for="avatarInput" class="btn btn-secondary btn-sm" style="margin-bottom:.5rem; cursor:pointer;">
                            <i class="fas fa-cloud-arrow-up"></i> Choose Photo
                        </label>
                        <p class="form-hint" style="margin-top:0.25rem;">JPG, PNG, WebP · Max 5 MB · Preview shows instantly</p>
                        @error('avatar') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Identity --}}
            <div class="card animate-fade-up" style="animation-delay:.1s;">
                <h4 style="margin-bottom:1.25rem;">Identity</h4>

                <div class="form-group">
                    <label class="form-label" for="name">Display Name <span style="color:var(--danger)">*</span></label>
                    <div class="input-group">
                        <i class="fas fa-user input-group-icon"></i>
                        <input type="text" name="name" id="name"
                               class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                               value="{{ old('name', $user->name) }}"
                               placeholder="Your full name" required maxlength="100">
                    </div>
                    @error('name') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="username">Username <span style="color:var(--danger)">*</span></label>
                    <div style="display:flex;align-items:stretch;">
                        <span style="display:flex;align-items:center;padding:0 .875rem;background:rgba(255,255,255,.04);border:1px solid var(--border-input);border-right:none;border-radius:var(--radius) 0 0 var(--radius);color:var(--text-muted);font-size:.875rem;white-space:nowrap;">
                            linkora.com/
                        </span>
                        <input type="text" name="username" id="username"
                               class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}"
                               style="border-radius:0 var(--radius) var(--radius) 0;"
                               value="{{ old('username', $user->username) }}"
                               placeholder="yourname" required minlength="3" maxlength="30"
                               pattern="[a-zA-Z0-9_]+" oninput="validateUsername(this)">
                    </div>
                    <p class="form-hint">3–30 chars. Letters, numbers, and underscores only.</p>
                    @error('username') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label" for="email">Email Address <span style="color:var(--danger)">*</span></label>
                    <div class="input-group">
                        <i class="fas fa-envelope input-group-icon"></i>
                        <input type="email" name="email" id="email"
                               class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                               value="{{ old('email', $user->email) }}"
                               required maxlength="255">
                    </div>
                    @error('email') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Bio & Website --}}
            <div class="card animate-fade-up" style="animation-delay:.15s;">
                <h4 style="margin-bottom:1.25rem;">About You</h4>

                <div class="form-group">
                    <label class="form-label" for="bio">
                        Bio
                        <span id="bioCount" style="float:right;font-weight:400;color:var(--text-muted);">
                            {{ strlen(old('bio', $user->bio ?? '')) }}/200
                        </span>
                    </label>
                    <textarea name="bio" id="bio"
                              class="form-control {{ $errors->has('bio') ? 'is-invalid' : '' }}"
                              placeholder="Tell the world a bit about yourself…"
                              maxlength="200"
                              oninput="document.getElementById('bioCount').textContent=this.value.length+'/200'">{{ old('bio', $user->bio) }}</textarea>
                    @error('bio') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label" for="website">Website</label>
                    <div class="input-group">
                        <i class="fas fa-globe input-group-icon"></i>
                        <input type="url" name="website" id="website"
                               class="form-control {{ $errors->has('website') ? 'is-invalid' : '' }}"
                               value="{{ old('website', $user->website) }}"
                               placeholder="https://yourwebsite.com" maxlength="255">
                    </div>
                    @error('website') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div style="display:flex;gap:.75rem;" class="animate-fade-up" style="animation-delay:.2s;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check"></i> Save Changes
                </button>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </div>

        {{-- Right: Live preview --}}
        <div style="position:sticky;top:1.5rem;display:flex;flex-direction:column;gap:1rem;">
            <div class="card card-glass" style="text-align:center;padding:2rem 1.25rem;">
                <p style="font-size:.68rem;text-transform:uppercase;letter-spacing:.1em;color:var(--text-muted);margin-bottom:1.25rem;">Live Preview</p>
                <img id="previewAvatar" src="{{ $user->avatarUrl() }}" alt="Preview"
                     style="width:76px;height:76px;border-radius:50%;object-fit:cover;margin:0 auto .875rem;border:3px solid var(--border-hover);">
                <div id="previewName" style="font-weight:700;font-size:1.05rem;">{{ $user->name }}</div>
                <div id="previewUsername" style="font-size:.8rem;color:var(--accent);margin-top:.2rem;">@{{ $user->username ?? 'username' }}</div>
                <p id="previewBio" style="font-size:.78rem;color:var(--text-secondary);margin-top:.625rem;min-height:1.5rem;">{{ $user->bio }}</p>
            </div>

            <div class="card" style="border-color:rgba(239,68,68,.2);">
                <h4 style="color:var(--danger);font-size:.9rem;margin-bottom:.625rem;">
                    <i class="fas fa-triangle-exclamation"></i> Danger Zone
                </h4>
                <p style="font-size:.78rem;color:var(--text-muted);margin-bottom:.875rem;">Permanently delete your account and all data.</p>
                <button type="button" onclick="document.getElementById('deleteModal').style.display='flex'"
                        class="btn btn-danger btn-sm w-full">
                    <i class="fas fa-trash"></i> Delete Account
                </button>
            </div>
        </div>

    </div>
</form>

{{-- Delete Modal --}}
<div id="deleteModal" class="modal-backdrop" style="display:none;" onclick="if(event.target===this)this.style.display='none'">
    <div class="modal">
        <div class="modal-header">
            <span class="modal-title" style="color:var(--danger);"><i class="fas fa-triangle-exclamation"></i> Delete Account</span>
            <button class="modal-close" onclick="document.getElementById('deleteModal').style.display='none'">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <p style="color:var(--text-secondary);font-size:.875rem;margin-bottom:1.25rem;">
            This is <strong style="color:var(--danger);">permanent and irreversible.</strong>
            All your links, analytics, and profile will be deleted immediately.
        </p>
        <form method="POST" action="{{ route('profile.destroy') }}">
            @csrf @method('DELETE')
            <div class="form-group">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="password" class="form-control" required placeholder="Enter your current password">
                @error('password', 'userDeletion') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('deleteModal').style.display='none'">Cancel</button>
                <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Delete Account</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('avatarPreview').src = e.target.result;
            document.getElementById('previewAvatar').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
function validateUsername(input) {
    const valid = /^[a-zA-Z0-9_]*$/.test(input.value);
    input.classList.toggle('is-invalid', !valid && input.value.length > 0);
}
document.getElementById('name')?.addEventListener('input', e => {
    document.getElementById('previewName').textContent = e.target.value || 'Your Name';
});
document.getElementById('username')?.addEventListener('input', e => {
    document.getElementById('previewUsername').textContent = '@' + (e.target.value || 'username');
});
document.getElementById('bio')?.addEventListener('input', e => {
    document.getElementById('previewBio').textContent = e.target.value;
});
@if($errors->userDeletion->isNotEmpty())
    document.getElementById('deleteModal').style.display = 'flex';
@endif
</script>
@endpush
