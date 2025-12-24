@extends('layouts.app')

@push('custom-css')
    <link rel="stylesheet" href="{{ asset('assets/modules/summernote/summernote-bs4.css') }}">
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Lowongan Kerja</h1>
        </div>

         <div class="section-body">
            <div class="card card-primary">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title text-right">Form Lowongan Kerja</h4>
                </div> 
                <form method="POST" action="{{ route('job.store') }}" autocomplete="off">  @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="judul">Judul</label>
                                <input id="judul" type="text" class="form-control @error('judul') is-invalid @enderror" name="judul">
                                @error('judul')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="perusahaan">Perusahaan</label>
                                <input id="perusahaan" type="text" class="form-control @error('perusahaan') is-invalid @enderror" name="perusahaan">
                                @error('perusahaan')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="lokasi">Lokasi</label>
                                <input id="lokasi" type="text" class="form-control @error('lokasi') is-invalid @enderror" name="lokasi">
                                @error('lokasi')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="date">Tanggal Tutup</label>
                                <input id="date" type="date" class="form-control @error('tanggal_tutup') is-invalid @enderror" name="tanggal_tutup">
                                @error('tanggal_tutup')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="date">Tipe</label>
                                <select name="tipe" id="tipe" class="custom-select @error('tipe') is-invalid @enderror">
                                    <option selected disabled>Pilih Tipe</option>
                                    <option value="part-time">Part-time</option>
                                    <option value="full-time">Full-time</option>
                                    <option value="contract">Contract</option>
                                </select>
                                @error('tipe')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                                <label for="date">Kategori</label>
                                <select name="category" id="category" class="custom-select @error('category') is-invalid @enderror">
                                    <option disabled>Pilih category</option>
                                    <option value="Programmer">Programmer</option>
                                    <option value="Analasis">Analisis</option>
                                    <option value="Designer">Designer</option>
                                </select>

                                @error('category')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea class="summernote" name="deskripsi">{{ old('deskripsi') }}</textarea>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="card-footer">
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('job.index') }}" class="btn btn-light ml-2">Kembali</a>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@push('custom-js')
    <script src="{{ asset('assets/modules/summernote/summernote-bs4.js') }}"></script>
@endpush