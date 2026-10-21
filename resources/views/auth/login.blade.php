<x-guest-layout>
    @section('title', 'Sign In')

    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-info" style="margin-bottom: 1.25rem;">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="form-group">
            <label class="form-label" for="email">Email</label>
            <div class="input-group">
                <i class="fas fa-envelope input-group-icon"></i>
                <input id="email" class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="name@example.com">
            </div>
            @error('email')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-group">
            <label class="form-label" for="password">Password</label>
            <div class="input-group">
                <i class="fas fa-lock input-group-icon"></i>
                <input id="password" class="form-control @error('password') is-invalid @enderror" type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
            </div>
            @error('password')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me & Forgot Password -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
            <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.825rem; color: var(--text-secondary); cursor: pointer;">
                <input type="checkbox" name="remember" style="accent-color: var(--accent);">
                <span>Remember me</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" style="font-size: 0.825rem; color: var(--accent); text-decoration: none;">
                    Forgot password?
                </a>
            @endif
        </div>

        <button type="submit" class="btn btn-primary w-full" style="padding: 0.75rem;">
            <i class="fas fa-right-to-bracket"></i> Sign In
        </button>

        <div class="auth-divider">or</div>

        <div style="text-align: center; font-size: 0.85rem; color: var(--text-secondary);">
            Don't have an account?
            <a href="{{ route('register') }}" style="color: var(--accent); font-weight: 600; text-decoration: none;">Create one now</a>
        </div>
    </form>
</x-guest-layout>
