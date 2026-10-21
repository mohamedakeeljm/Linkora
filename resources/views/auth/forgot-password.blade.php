<x-guest-layout>
    @section('title', 'Reset Password')

    <div style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 1.25rem; line-height: 1.5;">
        Forgot your password? No problem. Enter your email address and we will email you a password reset link.
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-info" style="margin-bottom: 1.25rem;">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="form-group">
            <label class="form-label" for="email">Email Address</label>
            <div class="input-group">
                <i class="fas fa-envelope input-group-icon"></i>
                <input id="email" class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="name@example.com">
            </div>
            @error('email')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary w-full" style="padding: 0.75rem; margin-top: 0.5rem;">
            <i class="fas fa-paper-plane"></i> Send Password Reset Link
        </button>

        <div class="auth-divider">or</div>

        <div style="text-align: center; font-size: 0.85rem; color: var(--text-secondary);">
            Remember your password?
            <a href="{{ route('login') }}" style="color: var(--accent); font-weight: 600; text-decoration: none;">Back to login</a>
        </div>
    </form>
</x-guest-layout>
