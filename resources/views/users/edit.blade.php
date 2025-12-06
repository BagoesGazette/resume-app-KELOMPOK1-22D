@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Pengguna</h1>
        </div>

         <div class="section-body">
            <div class="card card-primary">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title text-right">Form Pengguna</h4>
                </div> 
                <form method="POST" action="{{ route('users.update', $detail->id) }}" autocomplete="off">  @csrf @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="name">Nama</label>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $detail->name) }}">
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('name', $detail->email) }}">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="placeholderInput" class="form-label">Role</label>
                            <br>
                                @foreach ($roles as $role)
                                <div class="form-check form-check-inline mx-3 my-1">
                                    <label class="form-check-label" for="check-{{$role->id }}">
                                        <input type="checkbox" class="form-check-input" name="role[]"
                                            value="{{ $role->name }}" id="check-{{ $role->id }}" {{
                                            $detail->roles->contains($role->id) ? 'checked' : '' }}>
                                        {{ $role->name }}
                                    </label>
                                </div>
                                @endforeach
                        </div>
                    </div>

                </div>
                <div class="card-footer">
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('users.index') }}" class="btn btn-light ml-2">Kembali</a>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </section>
@endsection