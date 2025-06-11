@extends('layouts.dashboard')

@section('title', 'Tambah Kategori')

@push('styles')
    <link rel="stylesheet" href="{{ asset('modules/select2/dist/css/select2.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>@yield('title')</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ url('/dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">@yield('title')</div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ url('/categories') }}" method="POST">
                                @csrf
                                <div class="row g-4">
                                    <div class="form-group col-md-12">
                                        <label for="nama_kategori">Nama Kategori <span
                                                class="text-danger">*</span></label>
                                        <input id="nama_kategori" type="text"
                                            class="form-control @error('nama_kategori') is-invalid @enderror" name="nama_kategori"
                                            value="{{ old('nama_kategori') }}" placeholder="" autofocus>
                                        @error('nama_kategori')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        <div class="invalid-feedback" id="nama_kategori-error"></div>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label for="deskripsi">Deskripsi <span class="text-danger">*</span></label>
                                        <input id="deskripsi" type="text"
                                            class="form-control @error('deskripsi') is-invalid @enderror" name="deskripsi"
                                            value="{{ old('deskripsi') }}" placeholder="">
                                        @error('deskripsi')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <div class="d-flex justify-content-center justify-content-md-end align-items-center"
                                            style="gap: .5rem">
                                            <a href="{{ url('/categories') }}" class="btn btn-secondary">Kembali</a>
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
