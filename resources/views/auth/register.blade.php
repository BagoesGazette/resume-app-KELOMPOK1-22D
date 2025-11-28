@extends('layouts.auth') {{-- Menggunakan layout auth Stisla kita --}}

@section('content')
<div class="card card-primary">
    <div class="card-header"><h4>Register</h4></div>

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

        <form method="POST" action="{{ route('register') }}">
            @csrf <div class="form-group">
                <label for="name">Name</label>
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autofocus>
                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="d-block">Password</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="password_confirmation" class="d-block">Confirm Password</label>
                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required>
            </div>

            {{-- Stisla punya field "agree". Kita bisa skip ini, atau jika mau,
                 tambahkan validasi 'terms' di controller Breeze. 
                 Untuk sekarang, kita bisa komen/hapus bagian "terms" dari Stisla.
            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" name="agree" class="custom-control-input" id="agree">
                    <label class="custom-control-label" for="agree">I agree with the terms and conditions</label>
                </div>
            </div>
            --}}

            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-lg btn-block">
                    Register
                </button>
            </div>
        </form>
    </div>
</div>

<div class="mt-5 text-muted text-center">
    Already have an account? <a href="{{ route('login') }}">Login</a>
</div>
@endsection