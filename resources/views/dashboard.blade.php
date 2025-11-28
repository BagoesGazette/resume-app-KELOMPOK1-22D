@extends('layouts.app') {{-- INI BARIS KUNCINYA --}}

@section('title', 'Dashboard') {{-- Ini mengisi judul di header Stisla --}}

@section('content')
  {{-- Ini mengisi @yield('content') di layout Stisla --}}

  <div class="card">
    <div class="card-header">
      <h4>Selamat Datang!</h4>
    </div>
    <div class="card-body">
      <p>{{ __("You're logged in!") }}</p>
    </div>
  </div>

@endsection