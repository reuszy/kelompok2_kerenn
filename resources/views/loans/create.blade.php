@extends('layouts.dashboard')

@section('title', 'Tambah Peminjaman')

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
                      <form action="{{ url('/loans') }}" method="POST">
                        @csrf
                        <div class="row g-4">
              
                          {{-- Pilih Peminjam --}}
                          <div class="form-group col-md-6">
                            <label for="user_id">Peminjam <span class="text-danger">*</span></label>
                            {{-- <select id="user_id" name="user_id" class="form-control @error('user_id') is-invalid @enderror" required> --}}
                                {{-- @if (Auth::user()->role !== 'peminjam')

                              <option value="">-- Pilih Peminjam --</option>
                              @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                  {{ $user->username }}
                                </option>
                              @endforeach
                                @endif --}}
                                @if (Auth::user()->role !== 'peminjam')
                                <label for="user_id">Pilih Peminjam</label>
                                <select name="user_id" id="user_id" class="form-control">
                                  <option value="">-- Pilih Peminjam --</option>
                                  @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                      {{ $user->username }}
                                    </option>
                                  @endforeach
                                </select>
                              @else
                                {{-- <label for="user_id">Peminjam</label> --}}
                                <input type="text" class="form-control" value="{{ Auth::user()->username }}" readonly>
                                <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                              @endif


                            </select>
                            @error('user_id')
                              <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                          </div>
              
                          {{-- Kode Peminjaman --}}
                          <div class="form-group col-md-6">
                            <label for="kode_peminjaman">Kode Peminjaman <span class="text-danger">*</span></label>
                            <input id="kode_peminjaman" type="text" name="kode_peminjaman"
                                   class="form-control @error('kode_peminjaman') is-invalid @enderror"
                                   value="{{ old('kode_peminjaman') }}" place`holder="Masukkan kode peminjaman" required>
                            @error('kode_peminjaman')
                              <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                          </div>
              
                          {{-- Tanggal Peminjaman --}}
                          <div class="form-group col-md-6">
                            <label for="tanggal_peminjaman">Tanggal Peminjaman <span class="text-danger">*</span></label>
                            <input id="tanggal_peminjaman" type="date" name="tanggal_peminjaman"
                                   class="form-control @error('tanggal_peminjaman') is-invalid @enderror"
                                   value="{{ old('tanggal_peminjaman') ?? date('Y-m-d') }}" required>
                            @error('tanggal_peminjaman')
                              <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                          </div>
              
                          {{-- Tanggal Perkiraan Pengembalian --}}
                          <div class="form-group col-md-6">
                            <label for="tanggal_pengembalian">Perkiraan Tanggal Pengembalian</label>
                            <input id="tanggal_pengembalian" type="date" name="tanggal_pengembalian"
                                   class="form-control @error('tanggal_pengembalian') is-invalid @enderror"
                                   value="{{ old('tanggal_pengembalian') }}">
                            @error('tanggal_pengembalian')
                              <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                          </div>
              
                          {{-- Keterangan --}}
                          <div class="form-group col-md-12">
                            <label for="keterangan">Keterangan</label>
                            <textarea id="keterangan" name="keterangan"
                                      class="form-control @error('keterangan') is-invalid @enderror"
                                      rows="3">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                              <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                          </div>
              
                          {{-- Daftar Item yang dipinjam --}}
                          <div class="form-group col-md-12">
                            <label>Daftar Barang yang Dipinjam <span class="text-danger">*</span></label>
              
                            <table class="table table-bordered" id="loan-items-table">
                              <thead>
                                <tr>
                                  <th>Barang</th>
                                  <th>Jumlah</th>
                                  <th>Kondisi Saat Dipinjam</th>
                                  <th><button type="button" id="add-item" class="btn btn-success btn-sm">Tambah</button></th>
                                </tr>
                              </thead>
                              <tbody>
                                @if(old('items'))
                                  @foreach(old('items') as $index => $item)
                                    <tr>
                                      <td>
                                        <select name="items[{{ $index }}][item_id]" class="form-control" required>
                                          <option value="">-- Pilih Barang --</option>
                                          @foreach($items as $it)
                                            <option value="{{ $it->id }}" {{ $item['item_id'] == $it->id ? 'selected' : '' }}>{{ $it->nama_barang }}</option>
                                          @endforeach
                                        </select>
                                      </td>
                                      <td><input type="number" name="items[{{ $index }}][jumlah]" class="form-control" min="1" value="{{ $item['jumlah'] }}" required></td>
                                      <td><input type="text" name="items[{{ $index }}][kondisi_saat_dipinjam]" class="form-control" value="{{ $item['kondisi_saat_dipinjam'] }}"></td>
                                      <td><button type="button" class="btn btn-danger btn-sm remove-item">Hapus</button></td>
                                    </tr>
                                  @endforeach
                                @else
                                  <tr>
                                    <td>
                                      <select name="items[0][item_id]" class="form-control" required>
                                        <option value="">-- Pilih Barang --</option>
                                        @if(!empty($items) && count($items) > 0)
                                            @foreach($items as $item)
                                                <option value="{{ $item->id }}">{{ $item->nama_barang }}</option>
                                            @endforeach
                                        @else
                                            <option disabled selected>Tidak ada data barang</option>
                                        @endif
                                      </select>
                                    </td>
                                    <td><input type="number" name="items[0][jumlah]" class="form-control" min="1" value="1" required></td>
                                    <td><input type="text" name="items[0][kondisi_saat_dipinjam]" class="form-control"></td>
                                    <td><button type="button" class="btn btn-danger btn-sm remove-item">Hapus</button></td>
                                  </tr>
                                @endif
                              </tbody>
                            </table>
              
                            @error('items')
                              <div class="text-danger">{{ $message }}</div>
                            @enderror
                          </div>
              
                          <div class="col-12">
                            <div class="d-flex justify-content-center justify-content-md-end align-items-center" style="gap: .5rem">
                              <a href="{{ url('/loans') }}" class="btn btn-secondary">Kembali</a>
                              <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                          </div>
              
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              
        </section>
    </div>
@endsection
{{-- Script untuk tambah/hapus baris item --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
      let index = {{ old('items') ? count(old('items')) : 1 }};
      const tableBody = document.querySelector('#loan-items-table tbody');
      const addItemBtn = document.getElementById('add-item');
  
      addItemBtn.addEventListener('click', function () {
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
          <td>
            <select name="items[${index}][item_id]" class="form-control" required>
              <option value="">-- Pilih Barang --</option>
              @foreach($items as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
              @endforeach
            </select>
          </td>
          <td><input type="number" name="items[${index}][jumlah]" class="form-control" min="1" value="1" required></td>
          <td><input type="text" name="items[${index}][kondisi_saat_dipinjam]" class="form-control"></td>
          <td><button type="button" class="btn btn-danger btn-sm remove-item">Hapus</button></td>
        `;
        tableBody.appendChild(newRow);
        index++;
      });
  
      tableBody.addEventListener('click', function(e) {
        if(e.target && e.target.classList.contains('remove-item')) {
          const row = e.target.closest('tr');
          if(tableBody.rows.length > 1) {
            row.remove();
          } else {
            alert('Minimal harus ada 1 barang yang dipinjam.');
          }
        }
      });
    });
  </script>
