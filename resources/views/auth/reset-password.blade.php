@extends('layouts.auth') {{-- Menggunakan layout auth Stisla kita --}}

@section('content')
<div class="card card-primary">
    <div class="card-header"><h4>Reset Password</h4></div>

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

        <form method="POST" action="{{ route('password.store') }}"> {{-- Breeze 12 menggunakan route 'password.store' --}}
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $request->email ?? old('email') }}" required autofocus>
                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">New Password</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-lg btn-block">
                    Reset Password
                </button>
            </div>
        </form>
    </div>
</div>
@endsection