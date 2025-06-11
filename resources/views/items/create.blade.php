@extends('layouts.dashboard')

@section('title', 'Tambah Item')

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
                            <form action="{{ url('/items') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row g-4">
                                    {{-- Nama Barang --}}
                                    <div class="form-group col-md-6">
                                        <label for="nama_barang">Nama Barang <span class="text-danger">*</span></label>
                                        <input id="nama_barang" type="text"
                                            class="form-control @error('nama_barang') is-invalid @enderror"
                                            name="nama_barang" value="{{ old('nama_barang') }}" placeholder="Nama barang" required>
                                        @error('nama_barang')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
            
                                    {{-- Kode Barang --}}
                                    <div class="form-group col-md-6">
                                        <label for="kode_barang">Kode Barang <span class="text-danger">*</span></label>
                                        <input id="kode_barang" type="text"
                                            class="form-control @error('kode_barang') is-invalid @enderror"
                                            name="kode_barang" value="{{ old('kode_barang') }}" placeholder="Kode unik barang" required>
                                        @error('kode_barang')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
            
                                    {{-- Kategori --}}
                                    <div class="form-group col-md-6">
                                        <label for="kategori_id">Kategori <span class="text-danger">*</span></label>
                                        <select id="kategori_id" name="kategori_id"
                                            class="form-control @error('kategori_id') is-invalid @enderror" required>
                                            <option value="">-- Pilih Kategori --</option>
                                            @foreach($categories as $kategori)
                                                <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                                    {{ $kategori->nama_kategori }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('kategori_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
            
                                    {{-- Jumlah --}}
                                    <div class="form-group col-md-3">
                                        <label for="jumlah">Jumlah <span class="text-danger">*</span></label>
                                        <input id="jumlah" type="number"
                                            class="form-control @error('jumlah') is-invalid @enderror"
                                            name="jumlah" value="{{ old('jumlah') }}" min="1" required>
                                        @error('jumlah')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
            
                                    {{-- Satuan --}}
                                    <div class="form-group col-md-3">
                                        <label for="satuan">Satuan <span class="text-danger">*</span></label>
                                        <input id="satuan" type="text"
                                            class="form-control @error('satuan') is-invalid @enderror"
                                            name="satuan" value="{{ old('satuan') }}" placeholder="pcs, unit, dll" required>
                                        @error('satuan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
            
                                    {{-- Lokasi --}}
                                    <div class="form-group col-md-6">
                                        <label for="lokasi">Lokasi</label>
                                        <input id="lokasi" type="text"
                                            class="form-control @error('lokasi') is-invalid @enderror"
                                            name="lokasi" value="{{ old('lokasi') }}" placeholder="Contoh: Gudang A">
                                        @error('lokasi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
            
                                    {{-- Kondisi --}}
                                    <div class="form-group col-md-6">
                                        <label for="kondisi">Kondisi <span class="text-danger">*</span></label>
                                        <select id="kondisi" name="kondisi"
                                            class="form-control @error('kondisi') is-invalid @enderror" required>
                                            <option value="baik" {{ old('kondisi') == 'baik' ? 'selected' : '' }}>Baik</option>
                                            <option value="rusak ringan" {{ old('kondisi') == 'rusak ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                            <option value="rusak berat" {{ old('kondisi') == 'rusak berat' ? 'selected' : '' }}>Rusak Berat</option>
                                        </select>
                                        @error('kondisi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
            
                                    {{-- Status --}}
                                    <div class="form-group col-md-6">
                                        <label for="status">Status <span class="text-danger">*</span></label>
                                        <select id="status" name="status"
                                            class="form-control @error('status') is-invalid @enderror" required>
                                            <option value="tersedia" {{ old('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                            <option value="dipinjam" {{ old('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
            
                                    {{-- Deskripsi --}}
                                    <div class="form-group col-md-12">
                                        <label for="deskripsi">Deskripsi</label>
                                        <textarea id="deskripsi"
                                            class="form-control @error('deskripsi') is-invalid @enderror"
                                            name="deskripsi" placeholder="Deskripsi tambahan barang">{{ old('deskripsi') }}</textarea>
                                        @error('deskripsi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
            
                                    {{-- Foto --}}
                                    <div class="form-group col-md-12">
                                        <label for="foto">Foto Barang</label>
                                        <input id="foto" type="file"
                                            class="form-control @error('foto') is-invalid @enderror"
                                            name="foto" accept="image/*">
                                        @error('foto')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
            
                                    {{-- Tombol Simpan --}}
                                    <div class="col-12">
                                        <div class="d-flex justify-content-center justify-content-md-end align-items-center gap-2">
                                            <a href="{{ url('/items') }}" class="btn btn-secondary">Kembali</a>
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </div>
                                    </div>
                                </div> {{-- end row --}}
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
        </section>
    </div>
@endsection
