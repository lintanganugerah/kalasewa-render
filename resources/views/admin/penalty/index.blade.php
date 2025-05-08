@extends('admin.layout.app')

@section('title', 'Pengajuan Denda')

@section('content')

@php
    use Carbon\Carbon;
@endphp

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Pengajuan Denda</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Pengajuan Denda</li>
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
            <h5 class="card-title">Penalty Request</h5>
            @if ($penaltys->isEmpty())
                <p>Sedang tidak ada pengajuan tertunda.</p>
            @else
                <table class="table table-bordered" id="refunds-table">
                    <thead class="text-center">
                        <tr>
                            <th>Tanggal</th>
                            <th>No Order</th>
                            <th>Toko</th>
                            <th>Nama Penyewa</th>
                            <th>Peraturan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @foreach ($penaltys as $penalty)
                            <tr>
                                <td class="align-middle">
                                    {{ Carbon::parse($penalty->created_at)->translatedFormat('j F Y, H:i') }}
                                </td>
                                <td class="align-middle">{{ $penalty->nomor_order }}</td>
                                <td class="align-middle">{{ $penalty->toko->nama_toko }}</td>
                                <td class="align-middle">{{ $penalty->penyewa->nama }}</td>
                                <td class="align-middle">{{ $penalty->peraturan->nama }}</td>
                                <td class="align-middle">
                                    @if ($penalty->status == 'pending')
                                        <span class="badge badge-warning">{{ ucfirst($penalty->status) }}</span>
                                    @else
                                        {{ ucfirst($penalty->status) }}
                                    @endif
                                </td>
                                <td>
                                    @if ($penalty->status === 'pending')
                                        <a href="{{ route('admin.penalty.show', $penalty->id) }}"
                                            class="btn btn-primary btn-block">Detail</a>
                                    @else
                                        <p>Status tidak dikenali: {{ $penalty->status }}</p>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>

@endsection