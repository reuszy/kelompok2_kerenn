@extends('layouts.dashboard')

@if (Auth::user()->role == 'peminjam')

@section('title', 'Form Pengembalian Barang')
@else
@section('title', 'Form  Pengajuan Pengembalian Barang')

@endif
@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
        @if (Auth::user()->role !== 'peminjam')
            
            <h1>@yield('title')</h1>
        @else
            <h1> Pengajuan Pengembalian Barang</h1>

        @endif
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('returns.store') }}" method="POST">
                    @csrf

                    {{-- Info Peminjaman --}}
                    <div class="mb-3">
                        <label class="form-label"><strong>Kode Peminjaman:</strong></label>
                        <input type="text" class="form-control" value="{{ $loan->kode_peminjaman }}" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>Nama Peminjam:</strong></label>
                        <input type="text" class="form-control" value="{{ $loan->user->username }}" disabled>
                    </div>

                    {{-- Hidden input --}}
                    <input type="hidden" name="loan_id" value="{{ $loan->id }}">

                    @if (Auth::user()->role !== 'peminjam')
                    <div class="mb-3">
                        <label for="tanggal_pengembalian" class="form-label">Tanggal Pengembalian</label>
                        <input type="date" name="tanggal_pengembalian" class="form-control" required>
                    </div>
                    @else
                    {{-- Tanggal Pengembalian --}}
                    <div class="mb-3">
                        <label for="tanggal_pengembalian" class="form-label">Tanggal Pengembalian</label>
                        <input type="date" name="tanggal_pengembalian" class="form-control" value="{{ date('Y-m-d') }}" readonly>
                    </div>

                  
                    @endif


                    {{-- Kondisi Pengembalian --}}
                    <div class="mb-3">
                        <label for="kondisi_pengembalian" class="form-label">Kondisi Pengembalian</label>
                        <input type="text" name="kondisi_pengembalian" class="form-control" placeholder="Contoh: Baik, Rusak, dll">
                    </div>

                    {{-- Catatan --}}
                    <div class="mb-3">
                        <label for="catatan" class="form-label">Catatan</label>
                        <textarea name="catatan" class="form-control" rows="3"></textarea>
                    </div>

                    {{-- Denda --}}
                    @if (Auth::user()->role !== 'peminjam')

                    <div class="mb-3">
                        <label for="denda" class="form-label">Denda (jika ada)</label>
                        <input type="number" step="0.01" name="denda" class="form-control" value="0">
                    </div>

                    @endif

                    {{-- Submit --}}
                    <button type="submit" class="btn btn-success">Simpan Pengembalian</button>
                    <a href="{{ route('loans.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection
