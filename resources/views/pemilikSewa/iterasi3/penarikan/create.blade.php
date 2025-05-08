@extends('layout.selllayout')
@section('content')
    <div class="row">
        <div class="col">
            <a href="{{ route('seller.keuangan.dashboardKeuangan') }}" class="btn btn-outline-danger">Kembali</a>
            <div class="d-flex justify-content-between mb-5 mt-3">
                <div>
                    <h1 class="fw-bold text-secondary">Tarik Saldo</h1>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    <form action="{{ route('seller.penarikan-saldo.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class='form-group mb-3'>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">Rp</span>
                                </div>
                                <input type="number" class="form-control" placeholder="Minimal Rp. 10.000" id="nominal"
                                    aria-describedby="basic-addon1" name="nominal" min="10000"
                                    max="{{ auth()->user()->saldo_user->saldo }}" value="{{ old('nominal') }}"required>
                            </div>
                            @error('nominal')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-danger mb-3" id="nominalError"></div>
                        </div>
                        <div class="form-group form-check ml-1">
                            <input type="checkbox" class="form-check-input" id="semua"
                                value="{{ auth()->user()->saldo_user->saldo }}" onchange="checkSemua(this)">
                            <label class="form-check-label" for="semua">Tarik Semua Saldo (Rp.
                                {{ number_format(auth()->user()->saldo_user->saldo, 0, ',', '.') }})</label>
                        </div>
                        <div class='form-group mb-3'>
                            <label for='' class='mb-2'>Transfer Tujuan<span class='text-danger'>*</span></label>
                            <input type='text'
                                placeholder="{{ auth()->user()->saldo_user->tujuanRekening->nama }} | Atas Nama {{ auth()->user()->saldo_user->nama_rekening }} | {{ auth()->user()->saldo_user->nomor_rekening }}"
                                class='form-control' disabled>
                            @error('bank')
                                <div class="text-danger">Tujuan Transfer Ditemukan. Harap set tujuan transfer anda.</div>
                            @enderror
                        </div>
                        <p>Pastikan nomor rekening/e-wallet benar. Ingin mengubah tujuan transfer? <a
                                href="{{ route('seller.rekening.viewSetRekening') }}" class="text-danger">Klik disini</a>
                        </p>
                        <div class="form-group">
                            <button type="button" class="btn btnTarik btn-danger btn-block">Ajukan Penarikan Penghasilan
                                (Withdraw)</button>
                        </div>
                        <!-- Modal -->
                        <div class="modal fade" id="modalOTP" tabindex="-1" aria-labelledby="modalOTPpenarikan"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="modalOTPpenarikan">Masukan Kode OTP</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class='form-group mb-3'>
                                            <label for='kode_otp' class='mb-2'>Kode OTP<span
                                                    class='text-danger'>*</span></label>
                                            <input type='text' name='kode_otp' id='modal_kode_otp'
                                                class='form-control @error('kode_otp') is-invalid @enderror'
                                                placeholder="Masukan kode OTP yang telah dikirim" required>
                                        </div>
                                        <div class="mb-3 info">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" id="requestOtpButton">Request
                                                Kode OTP
                                                Lagi <span id="otpTimer"></span></button>
                                            <button type="button" onclick="submit()" class="btn btn-danger">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <meta name="ajax-url" content="{{ route('seller.kodeOTPpenarikan.send') }}">
            <meta name="email" content="{{ auth()->user()->email }}">
        </div>
    </div>
    <script src="{{ asset('seller/createPenarikan.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection
