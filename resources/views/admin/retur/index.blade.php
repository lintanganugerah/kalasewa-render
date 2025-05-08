@extends('admin.layout.app')

@section('title', 'Pengajuan Retur')

@section('content')

@php
    use Carbon\Carbon;
@endphp

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Pengajuan Retur</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Pengajuan Retur</li>
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

<div class="card mb-5">
    <div class="card-body">
        <div class="table-responsive">
            <h5 class="card-title">Return Request</h5>
            @if ($returs->isEmpty())
                <p>Sedang tidak ada permintaan pengajuan tertunda.</p>
            @else
                <table class="table table-bordered" id="orders-table">
                    <thead class="text-center">
                        <tr>
                            <th style="width: 180px">Tanggal</th>
                            <th>No Order</th>
                            <th>Nama Penyewa</th>
                            <th>Toko</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @foreach ($returs as $retur)
                            <tr>
                                <td class="align-middle">{{ Carbon::parse($retur->created_at)->translatedFormat('j F Y') }}</td>
                                <td class="align-middle">{{ $retur->nomor_order }}</td>
                                <td class="align-middle">{{ $retur->penyewa->nama }}</td>
                                <td class="align-middle">{{ $retur->produk->toko->nama_toko }}</td>
                                <td class="align-middle">
                                    @if ($retur->status == 'Pending')
                                        <span class="badge badge-danger">{{ ucfirst($retur->status) }}</span>
                                    @elseif ($retur->status == 'Retur')
                                        <span class="badge badge-warning">{{ ucfirst($retur->status) }}</span>
                                    @else
                                        {{ ucfirst($retur->status) }}
                                    @endif
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-block mb-2" data-toggle="modal"
                                        data-target="#returModal-{{ $retur->nomor_order }}">Tampilkan</button>
                                </td>
                            </tr>

                        @endforeach
                    </tbody>
                </table>
                <!-- Modal -->
                <div class="modal fade" id="returModal-{{ $retur->nomor_order }}" tabindex="-1" role="dialog"
                    aria-labelledby="returModalLabel-{{ $retur->nomor_order }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="returModalLabel-{{ $retur->nomor_order }}">
                                    Detail Retur
                                </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <th>Nomor Order</th>
                                                <td>{{ $retur->nomor_order }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tanggal Pengajuan</th>
                                                <td>{{ Carbon::parse($retur->updated_at)->translatedFormat('j F Y') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Nama Penyewa</th>
                                                <td>{{ $retur->penyewa->nama }}</td>
                                            </tr>
                                            <tr>
                                                <th>Nomor Penyewa</th>
                                                <td>{{ $retur->user->no_telp }}</td>
                                            </tr>
                                            <tr>
                                                <th>Nama Toko</th>
                                                <td>{{ $retur->produk->toko->nama_toko }}</td>
                                            </tr>
                                            <tr>
                                                <th>Nomor Toko</th>
                                                <td>{{ $retur->toko->user->no_telp }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <form action="{{ route('admin.retur.complete', $retur->nomor_order) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success">Konfirmasi</button>
                                </form>
                                <a href="{{ route('admin.retur.reject', ['nomor_order' => $retur->nomor_order]) }}"
                                    class="btn btn-danger">Tolak</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection