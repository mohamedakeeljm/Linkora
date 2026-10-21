@extends('layouts.app')
@section('title', 'Account Settings')

@section('content')

<div class="topbar animate-fade-up">
    <div class="topbar-title">
        <h1>Account <span class="text-gradient">Settings</span></h1>
        <p>Update password and credentials security.</p>
    </div>
</div>

<div class="card animate-fade-up" style="max-width: 600px; animation-delay:.05s;">
    <h4 style="margin-bottom: 1.25rem;">Update Password</h4>

    <form method="POST" action="{{ route('settings.update') }}">
        @csrf
        @method('PATCH')

        <div class="form-group">
            <label class="form-label" for="current_password">Current Password</label>
            <div class="input-group">
                <i class="fas fa-lock input-group-icon"></i>
                <input type="password" name="current_password" id="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
            </div>
            @error('current_password') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password">New Password</label>
            <div class="input-group">
                <i class="fas fa-key input-group-icon"></i>
                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required minlength="8">
            </div>
            @error('password') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password_confirmation">Confirm New Password</label>
            <div class="input-group">
                <i class="fas fa-check-double input-group-icon"></i>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required minlength="8">
            </div>
        </div>

        <div style="display:flex; gap:0.75rem; margin-top:1.5rem;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-shield-alt"></i> Save Password
            </button>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

@endsection
