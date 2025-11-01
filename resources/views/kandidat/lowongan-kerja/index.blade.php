@extends('layouts.app')

@section('title', 'Lowongan Kerja')

@section('content')
    <div class="section-header">
        <h1>Lowongan Kerja</h1>
    </div>

    <div class="row">
        @forelse($lowongan as $item)
            <div class="col-12 col-md-6 col-lg-6">
                <article class="article article-style-c">
                    <div class="article-details">
                        <div class="article-category">
                            <a href="#">{{ $item->kategori ?? 'General' }}</a>
                            <div class="bullet"></div>
                            <a href="#">Updated {{ $item->updated_at->diffForHumans() }}</a>
                        </div>
                        <div class="article-title">
                            <h2><a href="{{ route('lowongan.show', $item->id) }}">{{ $item->judul }}</a></h2>
                        </div>
                        <p class="font-weight-600 mb-2">{{ $item->perusahaan }}</p>
                        <p class="text-muted mb-2">
                            <i class="fas fa-map-marker-alt"></i> {{ $item->lokasi }} ({{ $item->tipe_kerja }})
                        </p>
                        <p>{{ Str::limit($item->deskripsi, 100) }}</p>
                    </div>
                </article>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Belum ada lowongan kerja tersedia saat ini.
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination jika diperlukan --}}
    @if($lowongan->hasPages())
        <div class="row">
            <div class="col-12">
                {{ $lowongan->links() }}
            </div>
        </div>
    @endif
@endsection