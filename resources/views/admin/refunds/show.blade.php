@extends('admin.layout.app')

@section('title')
    @if($refund->user->role == 'penyewa')
        Detail Pengembalian Dana
    @elseif($refund->user->role == 'pemilik_sewa')
        Detail Pencairan Dana
    @endif
@endsection

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            @if($refund->user->role == 'penyewa')
                Detail Pengembalian Dana
            @elseif($refund->user->role == 'pemilik_sewa')
                Detail Pencairan Dana
            @endif
        </h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail Pengembalian Dana</li>
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

    <div class="card mb-5">
        <div class="card-body">
            <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($refund->created_at)->translatedFormat('j F Y') }}</p>
            <p><strong>Nama:</strong> {{ $refund->user->nama }}</p>
            <p><strong>Nominal:</strong> {{ number_format($refund->nominal, 2, ',', '.') }}</p>
            <p><strong>Bank:</strong> {{ $refund->bank }}</p>
            <p><strong>Nomor Rekening:</strong> {{ $refund->nomor_rekening }}</p>
            <p><strong>Nama Rekening:</strong> {{ $refund->nama_rekening }}</p>

            @if ($refund->status == 'Sedang Diproses')
                <form action="{{ route('admin.refunds.transfer', $refund->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="bukti_transfer"><strong>Upload Bukti Transfer</strong></label>
                        <input type="file" class="form-control" name="bukti_transfer" id="bukti_transfer"
                            accept=".jpg,.png,.jpeg,.pdf" required>
                        <div id="bukti_transfer_alert" class="alert alert-danger mt-2" style="display: none;">
                            Silakan unggah bukti transfer.
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success btn-block" id="btn-submit">Kirim</button>
                </form>
                @if ($refund->status == 'Sedang Diproses')
                    <button type="button" class="btn btn-danger btn-block mt-2" data-toggle="modal"
                        data-target="#rejectModal">Tolak</button>
                @endif
            @elseif ($refund->status == 'Selesai' || $refund->status == 'Ditolak')
                <div class="form-group">
                    <label><strong>Bukti Transfer:</strong></label>
                    @if (!empty($refund->bukti_transfer))
                        <a href="{{ asset($refund->bukti_transfer) }}" target="_blank">Lihat Bukti Transfer</a>
                    @else
                        <p>Tidak ada bukti transfer.</p>
                    @endif
                </div>
                @if ($refund->status == 'Ditolak')
                    <div class="form-group">
                        <label><strong>Alasan Penolakan:</strong></label>
                        <p>{{ $refund->alasan_penolakan }}</p>
                    </div>
                @endif
            @endif
            
            <a href="{{ url()->previous() }}" class="btn btn-secondary btn-block mt-2">Kembali</a>
        </div>
    </div>

    <!-- Alasan Penolakan Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Alasan Penolakan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="rejectForm" action="{{ route('admin.refunds.reject') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" id="refund_id" value="{{ $refund->id }}">
                        <div class="form-group">
                            <label for="alasan_penolakan">Alasan Penolakan</label>
                            <select class="form-control" id="alasan_penolakan" name="alasan_penolakan">
                                <option value="Nomor Rekening tidak valid">Nomor Rekening tidak valid</option>
                                <option value="Nama Rekening tidak sesuai">Nama Rekening tidak sesuai</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-danger">Kirim</button>
                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#bukti_transfer').on('change', function() {
                var fileInput = $(this)[0];
                if (fileInput.files.length > 0) {
                    $('#btn-submit').prop('disabled', false);
                } else {
                    $('#btn-submit').prop('disabled', true);
                }
            });
        });
    </script>
@endsection
