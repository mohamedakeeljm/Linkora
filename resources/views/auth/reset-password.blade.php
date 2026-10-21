<x-guest-layout>
    @section('title', 'Set New Password')

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="form-group">
            <label class="form-label" for="email">Email Address</label>
            <div class="input-group">
                <i class="fas fa-envelope input-group-icon"></i>
                <input id="email" class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
            </div>
            @error('email')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-group">
            <label class="form-label" for="password">New Password</label>
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
            <i class="fas fa-key"></i> Reset Password
        </button>
    </form>
</x-guest-layout>
