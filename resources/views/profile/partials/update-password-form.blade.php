<form method="post" action="{{ route('password.update') }}">
    @csrf
    @method('put')

    <div class="form-group">
        <label for="current_password">{{ __('Current Password') }}</label>
        <input id="current_password" name="current_password" type="password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" required>
        @error('current_password', 'updatePassword')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="form-group">
        <label for="password">{{ __('New Password') }}</label>
        <input id="password" name="password" type="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" required>
        @error('password', 'updatePassword')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="form-group">
        <label for="password_confirmation">{{ __('Confirm Password') }}</label>
        <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" required>
    </div>

    <div class="card-footer text-right p-0">
        @if (session('status') === 'password-updated')
           <span class="text-success mr-3">{{ __('Saved.') }}</span>
        @endif
        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
    </div>
</form>