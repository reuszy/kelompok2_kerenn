@extends('layouts.dashboard')

@section('title', 'Data Pengembalian')

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

                            <div class="table-responsive">
                                <table class="table-striped table" id="table-returns">
                                    <thead>
                                        <tr class="text-center">
                                            <th>No.</th>
                                            <th>Kode Peminjaman</th>
                                            <th>Nama Peminjam</th>
                                            <th>Tanggal Pengembalian</th>
                                            <th>Kondisi Pengembalian</th>
                                            <th>Catatan</th>
                                            <th>Denda</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($returns as $return)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td class="text-center">{{ $return->loan->kode_peminjaman ?? '-' }}</td>
                                                <td>{{ $return->loan->user->username ?? '-' }}</td>
                                                <td class="text-center">{{ $return->tanggal_pengembalian }}</td>
                                                <td>{{ $return->kondisi_pengembalian ?? '-' }}</td>
                                                <td>{{ $return->catatan ?? '-' }}</td>
                                                <td class="text-right">{{ number_format($return->denda, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{ $returns->links() }} {{-- pagination --}}
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
@endpush
