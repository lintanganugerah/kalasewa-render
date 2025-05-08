@extends('admin.layout.app')

@section('title', 'Manajemen Dana')

@section('content')

@php
    use Carbon\Carbon;
@endphp

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Manajemen Dana</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Manajemen Dana</li>
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
            <h5 class="card-title">Refund & Withdraw Request</h5>
            @if ($refunds->isEmpty())
                <p>Sedang tidak ada permintaan pengembalian dana tertunda.</p>
            @else
                <table class="table table-bordered" id="refunds-table">
                    <thead class="text-center">
                        <tr>
                            <th style="width: 180px">Tanggal</th>
                            <th>Nama</th>
                            <th style="width: 100px">Nominal</th>
                            <th style="width: 310px">Bank</th>
                            <th style="width: 100px">Status</th>
                            <th style="text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @foreach ($refunds as $refund)
                            <tr>
                                <td class="align-middle">{{ Carbon::parse($refund->created_at)->translatedFormat('j F Y') }}
                                </td>
                                <td class="align-middle">{{ $refund->user->nama }}</td>
                                <td class="align-middle">{{ number_format($refund->nominal, 2, ',', '.') }}</td>
                                <td class="align-middle">{{ $refund->bank }}</td>
                                <td class="align-middle">
                                    @if ($refund->status == 'Menunggu Konfirmasi')
                                        <span class="badge badge-primary">{{ ucfirst($refund->status) }}</span>
                                    @elseif ($refund->status == 'Sedang Diproses')
                                        <span class="badge badge-warning">{{ ucfirst($refund->status) }}</span>
                                    @else
                                        {{ ucfirst($refund->status) }}
                                    @endif
                                </td>
                                <td>
                                    @if ($refund->status === 'Menunggu Konfirmasi')
                                        <form action="{{ route('admin.refunds.process') }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="refund_id" value="{{ $refund->id }}">
                                            <button type="submit" class="btn btn-primary btn-block">Proses</button>
                                        </form>
                                    @elseif ($refund->status === 'Sedang Diproses')
                                        <a href="{{ route('admin.refunds.show', ['id' => $refund->id]) }}"
                                            class="btn btn-success btn-block">Transfer</a>
                                    @else
                                        <p>Status tidak dikenali: {{ $refund->status }}</p>
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

<div class="card mb-5">
    <div class="card-body">
        <div class="table-responsive">
            <h5 class="card-title">History</h5>
            @if ($refundsHistory->isEmpty())
                <p>Belum ada pengembalian dana.</p>
            @else
                <table class="table table-bordered" id="refunds-table">
                    <thead class="text-center">
                        <tr>
                            <th style="width: 180px">Dibuat</th>
                            <th>Nama</th>
                            <th style="width: 100px">Nominal</th>
                            <th style="width: 304px">Bank</th>
                            <th style="width: 100px">Status</th>
                            <th style="text-align: center; width: 14%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @foreach ($refundsHistory as $refund)
                            <tr>
                                <td class="align-middle">{{ Carbon::parse($refund->created_at)->translatedFormat('j F Y') }}
                                </td>
                                <td class="align-middle">{{ $refund->user->nama }}</td>
                                <td class="align-middle">{{ number_format($refund->nominal, 2, ',', '.') }}</td>
                                <td class="align-middle">{{ $refund->bank }}</td>
                                <td class="align-middle">
                                    @if ($refund->status === 'Selesai')
                                        <span class="badge badge-success">{{ ucfirst($refund->status) }}</span>
                                    @elseif ($refund->status === 'Ditolak')
                                        <span class="badge badge-danger">{{ ucfirst($refund->status) }}</span>
                                    @else
                                        {{ ucfirst($refund->status) }}
                                    @endif
                                </td>
                                <td>
                                    @if ($refund->status === 'Selesai' || $refund->status === 'Ditolak')
                                        <a href="{{ route('admin.refunds.show', ['id' => $refund->id]) }}"
                                            class="btn btn-info btn-block">Lihat Detail</a>
                                    @else
                                        <p>Status tidak dikenali: {{ $refund->status }}</p>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div style="display: flex; justify-content: center; margin: 20px 0;">
                    {{ $refundsHistory->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@endsection