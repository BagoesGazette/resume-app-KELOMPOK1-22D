@extends('layouts.auth') {{-- Menggunakan layout auth Stisla kita --}}

@section('content')
<div class="card card-primary">
    <div class="card-header"><h4>Forgot Password</h4></div>

    <div class="card-body">
        
        <p class="text-muted">We will send a link to reset your password</p>

        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus tabindex="1">
                
                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                    Send Password Reset Link
                </button>
            </div>
        </form>
    </div>
</div>

<div class="mt-5 text-muted text-center">
    Remembered your password? <a href="{{ route('login') }}">Back to Login</a>
</div>
@endsection