@extends('admin.layout.app')

@section('title', 'Dashboard')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Kelola Daftar Bank</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Kelola Daftar Bank</li>
    </ol>
</div>
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<div class="row">
    <div class="col-md-6 mb-3">
        <a href="{{ route('admin.banks.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle mr-2"></i> Tambah Bank
        </a>
    </div>
    <div class="col-md-6 mb-3 d-flex justify-content-end">
        <form action="{{ route('admin.banks.index') }}" class="form-inline" method="GET">
            <input type="search" name="search" class="form-control mr-2" placeholder="Search"
                value="{{ request('search') }}">
            <button type="submit" class="btn btn-default">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>
</div>

<div class="table-responsive">
    @if ($banks->isEmpty())
        <div class="text-center mt-5">
            <p class="text-muted">Nama Bank tidak ditemukan.</p>
        </div>
    @else
        <table class="table table-data" id="banks-table">
            <thead>
                <tr>
                    <th>Nama Bank</th>
                    <th colspan="2" style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($banks as $banksItem)
                    <tr>
                        <td>{{ $banksItem->nama }}</td>
                        <td width="8%">
                            <a href="{{ route('admin.banks.edit', $banksItem->id) }}" class="btn btn-warning btn-block">Edit</a>
                        </td>
                        <td width="8%">
                            <button class="btn btn-danger btn-block" data-toggle="modal"
                                data-target="#confirmDeleteModal{{ $banksItem->id }}">Hapus</button>
                        </td>
                    </tr>

                    <!-- Modal Konfirmasi Delete -->
                    <div class="modal fade" id="confirmDeleteModal{{ $banksItem->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="confirmDeleteModalLabel{{ $banksItem->id }}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="confirmDeleteModalLabel{{ $banksItem->id }}">Konfirmasi Hapus
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    Apakah Anda yakin ingin menghapus nama bank ini?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                    <form action="{{ route('admin.banks.destroy', $banksItem->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    @endif

    <div style="display: flex; justify-content: center; margin: 20px 0;">
        {{ $banks->appends(request()->query())->links() }}
    </div>
</div>

@endsection