@extends('layouts.dashboard')

@section('title', 'Data Item')

@push('styles')
    <link rel="stylesheet" href="{{ asset('modules/datatables/dataTables.min.css') }}">
    <style>
        .table {
            white-space: nowrap !important;
        }
    </style>
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
                                @if (Auth::user()->role !== 'peminjam')
                                    <div class=" d-flex justify-content-between align-items-center mb-4">
                                        <a href="{{ url('/items/create') }}"
                                            class="btn btn-primary ml-auto">Tambah</a>
                                    </div>
                                @endif

                            <div class="table-responsive">
                                <table class="table-striped table" id="table-1">
                                    <thead>
                                        <tr class="text-center">
                                            <th>No.</th>
                                            <th>Nama Barang</th>
                                            <th>Kode</th>
                                            @if (Auth::user()->role ==  'admin')
                                            <th>Aksi</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($items as $ctr)
                                            <tr>
                                                <td class="text-right">{{ $loop->iteration }}</td>
                                                <td class="text-right">{{ $ctr->nama_barang  }}</td>
                                                <td class="text-right">{{ $ctr->kode_barang }}</td>
                                                <td>
                                                    <div class="d-flex justify-content-center" style="gap: .5rem">
                                                        {{-- <a href="{{ url("/items/{$ctr->id}/show") }}"
                                                            class="btn btn-info" data-toggle="tooltip" title="Detail">
                                                            <i class="fas fa-info-circle"></i>
                                                        </a> --}}
                                                        @if (Auth::user()->role ==  'admin')
                                                            <a href="{{ url("/items/{$ctr->id}/edit") }}"
                                                                class="btn btn-primary" data-toggle="tooltip"
                                                                title="Ubah">
                                                                <i class="fas fa-pencil"></i>
                                                            </a>
                                                            <form action="{{ url("/items/{$ctr->id}") }}"
                                                                method="POST" id="delete-form-{{ $ctr->id }}"
                                                                class="d-inline">
                                                                @method('delete')
                                                                @csrf
                                                                <button type="submit" class="btn btn-danger btn-delete"
                                                                    data-toggle="tooltip" title="Hapus">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('modules/datatables/dataTables.min.js') }}"></script>
    <script src="{{ asset('js/page/modules-datatables.js') }}"></script>

    @if (Auth::user()->officer_id !== null)
        @if (Auth::user()->officers->position !== 'Lurah' && Auth::user()->officers->position !== 'Kepala Lingkungan')
            <script>
                $(document).ready(function() {
                    // Gunakan delegasi untuk tombol hapus
                    $(document).on('click', '.btn-delete', function(e) {
                        e.preventDefault();

                        const formId = $(this).closest('form').attr('id');

                        swal({
                            title: 'Hapus Data',
                            text: 'Apakah Anda yakin ingin menghapus data ini?',
                            icon: 'warning',
                            buttons: {
                                cancel: 'Batal',
                                confirm: {
                                    text: 'Ya, Hapus!',
                                    value: true,
                                    className: 'btn-danger',
                                }
                            },
                            dangerMode: true,
                        }).then((willDelete) => {
                            if (willDelete) {
                                $('#' + formId).submit();
                            }
                        });
                    });
                });
            </script>
        @endif
    @endif
@endpush
