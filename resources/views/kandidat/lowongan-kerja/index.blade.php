@extends('layouts.app')

@section('title', 'Lowongan Kerja')

@section('content')
    <div class="row">
        {{-- Contoh Kartu Lowongan 1 --}}
        <div class="col-12 col-md-6 col-lg-6">
            <article class="article article-style-c">
                <div class="article-details">
                    <div class="article-category">
                        <a href="#">Web & Interaction Design</a>
                        <div class="bullet"></div>
                        <a href="#">Updated 3hrs ago</a>
                    </div>
                    <div class="article-title">
                        <h2><a href="#">Freelance UI UX Designer (Fully Remote)</a></h2>
                    </div>
                    <p class="font-weight-600 mb-2">Hax Capital Pty Ltd</p>
                    <p class="text-muted mb-2">
                        <i class="fas fa-map-marker-alt"></i> Jakarta (Remote)
                    </p>
                    <p>This is a freelance UI/UX Designer role, compensated on a per-project basis.</p>
                </div>
            </article>
        </div>

        {{-- Contoh Kartu Lowongan 2 --}}
        <div class="col-12 col-md-6 col-lg-6">
            <article class="article article-style-c">
                <div class="article-details">
                    <div class="article-category">
                        <a href="#">Software Engineering</a>
                        <div class="bullet"></div>
                        <a href="#">Updated 1 day ago</a>
                    </div>
                    <div class="article-title">
                        <h2><a href="#">Senior Backend Developer (Onsite)</a></h2>
                    </div>
                    <p class="font-weight-600 mb-2">Digital Solutions Inc.</p>
                    <p class="text-muted mb-2">
                        <i class="fas fa-map-marker-alt"></i> Bandung (Onsite)
                    </p>
                    <p>We are looking for an experienced Backend Developer to join our growing team.</p>
                </div>
            </article>
        </div>

        {{-- Contoh Kartu Lowongan 3 --}}
        <div class="col-12 col-md-6 col-lg-6">
            <article class="article article-style-c">
                <div class="article-details">
                    <div class="article-category">
                        <a href="#">Marketing</a>
                        <div class="bullet"></div>
                        <a href="#">Updated 2 days ago</a>
                    </div>
                    <div class="article-title">
                        <h2><a href="#">Digital Marketing Specialist</a></h2>
                    </div>
                    <p class="font-weight-600 mb-2">Creative Agency</p>
                    <p class="text-muted mb-2">
                        <i class="fas fa-map-marker-alt"></i> Surabaya (WFO)
                    </p>
                    <p>Join our marketing team to create and manage campaigns across various digital channels.</p>
                </div>
            </article>
        </div>
    </div>
@endsection