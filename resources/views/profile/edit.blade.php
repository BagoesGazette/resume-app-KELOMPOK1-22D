@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
    <div class="section-header">
        <h1>Profile</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
            <div class="breadcrumb-item">Profile</div>
        </div>
    </div>
    
    <div class="section-body">
        <h2 class="section-title">Hi, {{ auth()->user()->name }}!</h2>
        <p class="section-lead">
            Ubah informasi tentang diri Anda di halaman ini.
        </p>

        <div class="row mt-sm-4">
            {{-- KOLOM KIRI --}}
            <div class="col-12 col-md-12 col-lg-6">
                
                {{-- Card 1: Informasi Profil --}}
                <div class="card">
                    <div class="card-header">
                        <h4>Informasi Profil</h4>
                    </div>
                    <div class="card-body">
                        {{-- Partial ini berisi form, kita akan perbaiki di Langkah 2 --}}
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                {{-- Card 2: Update Password --}}
                <div class="card">
                    <div class="card-header">
                        <h4>Update Password</h4>
                    </div>
                    <div class="card-body">
                        {{-- Partial ini juga akan kita perbaiki nanti --}}
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

            </div>

            {{-- KOLOM KANAN --}}
            <div class="col-12 col-md-12 col-lg-6">
                
                {{-- Card 3: Hapus Akun --}}
                <div class="card card-danger">
                    <div class="card-header">
                        <h4>Hapus Akun</h4>
                    </div>
                    <div class="card-body">
                        {{-- Partial ini juga akan kita perbaiki nanti --}}
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection