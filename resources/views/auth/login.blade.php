@extends('layouts.auth') {{-- Menggunakan layout auth Stisla kita --}}

@section('content')
<div class="card card-primary">
    <div class="card-header"><h4>Login</h4></div>

    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                {{ __('Whoops! Something went wrong.') }}
                <ul class="mt-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate="">
            @csrf <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <div class="d-block">
                    <label for="password" class="control-label">Password</label>
                    @if (Route::has('password.request'))
                    <div class="float-right">
                        <a href="{{ route('password.request') }}" class="text-small">
                            Forgot Password?
                        </a>
                    </div>
                    @endif
                </div>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" name="remember" class="custom-control-input" tabindex="3" id="remember-me">
                    <label class="custom-control-label" for="remember-me">Remember Me</label>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                    Login
                </button>
            </div>
        </form>

    </div>
</div>

<div class="mt-5 text-muted text-center">
    Don't have an account? <br>
    <a href="{{ route('register.kandidat') }}">Register as Kandidat</a>
    |
    <a href="{{ route('register.hr') }}">Register as HR</a>
</div>

@endsection