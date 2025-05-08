@extends('admin.layout.app')

@section('title', 'Ticket Pengguna')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Manajemen Ticket</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Manajemen Ticket</li>
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

<a href="{{ route('admin.ticket.category') }}" class="btn btn-primary mb-4">Lihat Kategori</a>

<div class="card mb-5">
    <div class="card-body">
        <h5 class="card-title">Ticket Request</h5>
        <div class="table-responsive">
            @if ($tickets->isEmpty())
                <p>Sedang tidak ada permintaan tiket.</p>
            @else
                <table class="table table-bordered" id="tickets-table">
                    <thead class="text-center align-middle">
                        <tr>
                            <th style="width: 5%;" class="text-center align-middle">No Tiket</th>
                            <th style="width: 15%;" class="text-center align-middle">Dibuat Tanggal</th>
                            <th style="width: 30%;" class="text-center align-middle">Nama</th>
                            <th style="width: 30%;" class="text-center align-middle">Kategori Tiket</th>
                            <th style="width: 10%;" class="text-center align-middle">Status</th>
                            <th style="width: 10%;" class="text-center align-middle">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-center align-middle">
                        @foreach ($tickets as $ticket)
                            <tr class="text-center align-middle">
                                <td class="text-center align-middle">{{ $ticket->id }}</td>
                                <td class="text-center align-middle">
                                    {{ \Carbon\Carbon::parse($ticket->updated_at)->translatedFormat('j F Y, H:i') }}
                                </td>
                                <td class="text-center align-middle">{{ $ticket->user->nama}}</td>
                                <td class="text-center align-middle">{{ $ticket->kategori->nama }}</td>
                                <td class="text-center align-middle">
                                    @if ($ticket->status === 'Menunggu Konfirmasi')
                                        <span class="badge badge-primary">{{ ucfirst($ticket->status) }}</span>
                                    @elseif ($ticket->status === 'Sedang Diproses')
                                        <span class="badge badge-warning">{{ ucfirst($ticket->status) }}</span>
                                    @else
                                        {{ ucfirst($ticket->status) }}
                                    @endif
                                </td>
                                <td class="text-center align-middle">
                                    <a href="{{ route('admin.ticket.show', $ticket->id) }}"
                                        class="btn btn-primary btn-block">Tampilkan</a>
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
        <h5 class="card-title">History</h5>
        <div class="table-responsive">
            @if ($completedOrRejectedTickets->isEmpty())
                <p>Belum ada tiket selesai atau ditolak.</p>
            @else
                <table class="table table-bordered" id="history-tickets-table">
                    <thead class="text-center align-middle">
                        <tr>
                            <th style="width: 5%;" class="text-center align-middle">No Tiket</th>
                            <th style="width: 15%;" class="text-center align-middle">Dibuat Tanggal</th>
                            <th style="width: 30%;" class="text-center align-middle">Nama</th>
                            <th style="width: 30%;" class="text-center align-middle">Kategori Tiket</th>
                            <th style="width: 10%;" class="text-center align-middle">Status</th>
                            <th style="width: 10%;" class="text-center align-middle">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-center align-middle">
                        @foreach ($completedOrRejectedTickets as $ticket)
                            <tr class="text-center align-middle">
                                <td class="text-center align-middle">{{ $ticket->id }}</td>
                                <td class="text-center align-middle">
                                    {{ \Carbon\Carbon::parse($ticket->created_at)->translatedFormat('j F Y, H:i') }}
                                </td>
                                <td class="text-center align-middle">{{ $ticket->user->name }}</td>
                                <td class="text-center align-middle">{{ $ticket->kategori->nama }}</td>
                                <td class="text-center align-middle">
                                    @if ($ticket->status === 'Ditolak')
                                        <span class="badge badge-danger">{{ ucfirst($ticket->status) }}</span>
                                    @elseif ($ticket->status === 'Selesai')
                                        <span class="badge badge-success">{{ ucfirst($ticket->status) }}</span>
                                    @else
                                        {{ ucfirst($ticket->status) }}
                                    @endif
                                </td>
                                <td class="text-center align-middle">
                                    <a href="{{ route('admin.ticket.show', $ticket->id) }}"
                                        class="btn btn-primary btn-block">Tampilkan</a>
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