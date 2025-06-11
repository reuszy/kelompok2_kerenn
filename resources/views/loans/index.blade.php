@extends('layouts.dashboard')

@section('title', 'Data Peminjaman')

@push('styles')
    <link rel="stylesheet" href="{{ asset('modules/datatables/dataTables.min.css') }}">
    <style>
        .table {
            white-space: nowrap !important;
        }
    </style>
@endpush

@section('main')fa-circle-xmark
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
                                {{-- @if (Auth::user()->role !== 'peminjam') --}}
                                    <div class=" d-flex justify-content-between align-items-center mb-4">
                                        <a href="{{ url('/loans/create') }}"
                                            class="btn btn-primary ml-auto">Tambah</a>
                                    </div>
                                {{-- @endif --}}

                                <div class="table-responsive">
                                    <table class="table-striped table" id="table-1">
                                        <thead>
                                            <tr class="text-center">
                                                <th>No.</th>
                                                <th>Kode Peminjaman</th>
                                                <th>Nama Peminjam</th>
                                                <th>Tanggal Peminjaman</th>
                                                <th>Tanggal Pengembalian</th>
                                                <th>Status</th>
                                                <th>Keterangan</th>
                                                {{-- @if (Auth::user()->role !== '') --}}
                                                <th>Aksi</th>
                                                {{-- @endif --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($loans as $loan)
                                                <tr>
                                                    <td class="text-center">{{ $loop->iteration }}</td>
                                                    <td class="text-center">{{ $loan->kode_peminjaman }}</td>
                                                    <td>{{ $loan->user->username ?? '-' }}</td>
                                                    <td class="text-center">{{ $loan->tanggal_peminjaman }}</td>
                                                    <td class="text-center">
                                                        {{ $loan->tanggal_pengembalian ? \Carbon\Carbon::parse($loan->tanggal_peminjaman)->format('Y-m-d') : '-' }}
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge 
                                                            @if($loan->status == 'pending') badge-warning
                                                            @elseif($loan->status == 'approved') badge-primary
                                                            @elseif($loan->status == 'returned') badge-success
                                                            @elseif($loan->status == 'late') badge-danger
                                                            @else badge-secondary
                                                            @endif">
                                                            {{ ucfirst($loan->status) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $loan->keterangan ?? '-' }}</td>
                                                    <td>
                                                        <div class="d-flex justify-content-center" style="gap: .5rem">
                                                            {{-- <a href="{{ route('loans.show', $loan->id) }}" class="btn btn-info" title="Detail">
                                                                <i class="fas fa-info-circle"></i>
                                                            </a> --}}
                                                            @if (Auth::user()->role == 'peminjam')

                                                            @if ($loan->status !== 'returned')
                                                                <a href="{{ route('return.create', $loan->id) }}" class="btn btn-primary" title="Pengembalian">
                                                                    <i class="fas fa-undo"></i>
                                                                </a>
                                                            @endif
                                                            @endif

                                                            @if (Auth::user()->role !== 'peminjam')
                                                                <a href="{{ route('loans.edit', $loan->id) }}" class="btn btn-secondary" title="Ubah">
                                                                    <i class="fas fa-pencil-alt"></i>
                                                                </a>
                                                            @if ($loan->status !== 'returned')
                                                                <a href="{{ route('return.create', $loan->id) }}" class="btn btn-primary" title="Pengembalian">
                                                                    <i class="fas fa-undo"></i>
                                                                </a>
                                                            @endif
                                                            @if ($loan->status == 'pending')
                                                            <form action="{{ route('loans.approval', $loan->id) }}" method="POST" class="d-inline"
                                                                onsubmit="return confirm('Yakin ingin mengapproval peminjaman ini?');">
                                                                @csrf
                                                                @method('PUT')
                                                                <button type="submit" class="btn btn-success" title="approval">
                                                                    <i class="fas fa-check"></i>
                                                                </button>
                                                            </form>
                                                            @endif
                                                            @if ($loan->status == 'Return Approval')
                                                            <form action="{{ route('loans.approval_return', $loan->id) }}" method="POST" class="d-inline"
                                                                onsubmit="return confirm('Yakin ingin mengapproval pengembalian ini?');">
                                                                @csrf
                                                                @method('PUT')
                                                                <button type="submit" class="btn btn-success" title="approval">
                                                                    <i class="fas fa-check"></i>
                                                                </button>
                                                            </form>
                                                            @endif
                                                                <form action="{{ route('loans.destroy', $loan->id) }}" method="POST" class="d-inline"
                                                                    onsubmit="return confirm('Yakin ingin menghapus peminjaman ini?');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger" title="Hapus">
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
                                    {{ $loans->links() }} {{-- pagination --}}
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
