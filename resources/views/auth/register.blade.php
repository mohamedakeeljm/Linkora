<x-guest-layout>
    @section('title', 'Create Account')

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="form-group">
            <label class="form-label" for="name">Full Name</label>
            <div class="input-group">
                <i class="fas fa-user input-group-icon"></i>
                <input id="name" class="form-control @error('name') is-invalid @enderror" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="John Doe">
            </div>
            @error('name')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email Address -->
        <div class="form-group">
            <label class="form-label" for="email">Email</label>
            <div class="input-group">
                <i class="fas fa-envelope input-group-icon"></i>
                <input id="email" class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="name@example.com">
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
                <input id="password" class="form-control @error('password') is-invalid @enderror" type="password" name="password" required autocomplete="new-password" placeholder="At least 8 characters">
            </div>
            @error('password')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
            <label class="form-label" for="password_confirmation">Confirm Password</label>
            <div class="input-group">
                <i class="fas fa-check-double input-group-icon"></i>
                <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm password">
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-full" style="padding: 0.75rem; margin-top: 0.5rem;">
            <i class="fas fa-user-plus"></i> Create Account
        </button>

        <div class="auth-divider">or</div>

        <div style="text-align: center; font-size: 0.85rem; color: var(--text-secondary);">
            Already have an account?
            <a href="{{ route('login') }}" style="color: var(--accent); font-weight: 600; text-decoration: none;">Sign in</a>
        </div>
    </form>
</x-guest-layout>
