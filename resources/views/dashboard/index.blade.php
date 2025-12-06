@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
          <h1>Dashboard</h1>
        </div>
        @role('admin')
          @include('dashboard.card-admin', compact('pengguna'))
        @else
          @include('dashboard.card-kandidat')
        @endrole
    </section>
@endsection