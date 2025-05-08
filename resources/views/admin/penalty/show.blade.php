@extends('admin.layout.app')

@section('title', 'Detail Pengajuan Denda')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Detail Pengajuan Denda</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Detail Pengajuan Denda</li>
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

@if ($errors->any())
    <div class="alert alert-danger">
        {{ $errors->first() }}
    </div>
@endif

@php
    use Carbon\Carbon;
@endphp

<div class="card mb-5">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <tr>
                    <th>Tanggal</th>
                    <td>{{ Carbon::parse($penalty->created_at)->translatedFormat('j F Y, H:i') }}</td>
                </tr>
                <tr>
                    <th>No Order</th>
                    <td>{{ $penalty->nomor_order }}</td>
                </tr>
                <tr>
                    <th>Nama Toko</th>
                    <td>{{ $penalty->toko->nama_toko }} ({{ $penalty->toko->user->no_telp }})</td>
                </tr>
                <tr>
                    <th>Nama Penyewa</th>
                    <td>{{ $penalty->penyewa->nama }} ({{ $penalty->penyewa->no_telp }})</td>
                </tr>
                <tr>
                    <th>Peraturan</th>
                    <td>{{ $penalty->peraturan->nama }}</td>
                </tr>
                <tr>
                    <th>Penjelasan</th>
                    <td>{{ $penalty->penjelasan }}</td>
                </tr>
                <tr>
                    <th>Nominal</th>
                    <td>Rp. {{ number_format($penalty->nominal_denda, 0, '', '.') }}</td>
                </tr>
                <tr>
                    <th>Foto Bukti</th>
                    <td>
                        @if($penalty->foto_bukti)
                            @if ($penalty->foto_bukti)
                                @foreach ($penalty->foto_bukti as $foto)
                                    <a href="{{ asset($foto) }}" target="_blank">Lihat Bukti Foto[{{ $loop->iteration }}]<br></a>
                                @endforeach
                            @else
                                <p> Tidak ada Bukti Foto </p>
                            @endif
                        @endif
                    </td>
                </tr>
            </table>

            <form action="{{ route('admin.penalty.confirm', $penalty->id) }}" method="POST" class="d-inline">
                @csrf
                <input type="hidden" name="penalty_id" value="{{ $penalty->id }}">
                <button type="submit" class="btn btn-primary mt-2 btn-block">Konfirmasi</button>
            </form>
            <form action="{{ route('admin.penalty.hitnrun', $penalty->id) }}" method="POST" class="d-inline">
                @csrf
                <input type="hidden" name="penalty_id" value="{{ $penalty->id }}">
                <button type="submit" class="btn btn-warning mt-2 btn-block">Penyewa Kabur</button>
            </form>
            <div class="mb-2 mt-2">
                <button type="button" class="btn btn-danger btn-block" data-toggle="modal"
                    data-target="#rejectModal">Tolak</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Alasan Penolakan -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">Konfirmasi Penolakan</h5>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.penalty.reject', $penalty->id) }}" method="POST">
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