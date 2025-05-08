@extends('admin.layout.app')

@section('title', 'Detail Ticket')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Detail Ticket</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.ticket.index') }}">Manajemen Ticket</a></li>
        <li class="breadcrumb-item active" aria-current="page">Detail Ticket</li>
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
            <table class="table table-bordered">
                <tr>
                    <th>Nomor Tiket</th>
                    <td>{{ $ticket->id }}</td>
                </tr>
                <tr>
                    <th>Dibuat Tanggal</th>
                    <td>{{ \Carbon\Carbon::parse($ticket->created_at)->translatedFormat('j F Y, H:i') }}</td>
                </tr>
                <tr>
                    <th>
                        @if ($ticket->status == 'Selesai' || $ticket->status == 'Ditolak')
                            Diselesaikan Tanggal
                        @else
                            Diproses Tanggal
                        @endif
                    </th>
                    <td>
                        @if ($ticket->status == 'Menunggu Konfirmasi')
                            <strong style="color: red;">Belum Diproses</strong>
                        @else
                            {{ \Carbon\Carbon::parse($ticket->updated_at)->translatedFormat('j F Y, H:i') }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Nama</th>
                    <td>{{ $ticket->user->name }}</td>
                </tr>
                <tr>
                    <th>Role</th>
                    <td>{{ $ticket->user->role }}</td>
                </tr>
                <tr>
                    <th>Kategori Tiket</th>
                    <td>{{ $ticket->kategori->nama }}</td>
                </tr>
                <tr>
                    <th>Deskripsi</th>
                    <td>{{ $ticket->deskripsi }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        @if ($ticket->status === 'Menunggu Konfirmasi')
                            <span class="badge badge-primary">{{ ucfirst($ticket->status) }}</span>
                        @elseif ($ticket->status === 'Sedang Diproses')
                            <span class="badge badge-warning">{{ ucfirst($ticket->status) }}</span>
                        @elseif ($ticket->status === 'Selesai')
                            <span class="badge badge-success">{{ ucfirst($ticket->status) }}</span>
                        @elseif ($ticket->status === 'Ditolak')
                            <span class="badge badge-danger">{{ ucfirst($ticket->status) }}</span>
                        @else
                            {{ ucfirst($ticket->status) }}
                        @endif
                    </td>
                </tr>
                @if($ticket->status == 'Ditolak')
                    <tr>
                        <th>Alasan Penolakan</th>
                        <td>{{ $ticket->alasan_penolakan }}</td>
                    </tr>
                @endif
                @if($ticket->bukti_tiket)
                    <tr>
                        <th>Bukti Tiket</th>
                        <td>
                            @if ($ticket->bukti_tiket)
                                @foreach ($ticket->bukti_tiket as $foto)
                                    <a href="{{ asset($foto) }}" target="_blank">Lihat Bukti Tiket[{{ $loop->iteration }}]<br></a>
                                @endforeach
                            @else
                                <p> Tidak ada Foto Bukti </p>
                            @endif
                        </td>
                    </tr>
                @endif
            </table>
            @if ($ticket->status == 'Menunggu Konfirmasi')
                <div class="mb-2 mt-4">
                    <form action="{{ route('admin.ticket.process', $ticket->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success btn-block">Proses</button>
                    </form>
                </div>
            @elseif ($ticket->status == 'Sedang Diproses')
                <div class="mb-2 mt-4">
                    <form action="{{ route('admin.ticket.complete', $ticket->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success btn-block">Selesaikan</button>
                    </form>
                </div>
                <div class="mb-2 mt-2">
                    <button type="button" class="btn btn-danger btn-block" data-toggle="modal"
                        data-target="#rejectModal">Tolak</button>
                </div>
            @endif
            <a href="{{ route('admin.ticket.index') }}" class="btn btn-secondary btn-block mb-3  mt-2">Kembali</a>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">Konfirmasi Penolakan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.ticket.reject', $ticket->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="alasan_penolakan">Alasan Penolakan</label>
                        <textarea class="form-control" id="alasan_penolakan" name="alasan_penolakan"
                            required></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger">Tolak</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection