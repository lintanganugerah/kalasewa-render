@extends('layout.selllayout')

@section('content')
    <div class="row">
        <div class="col">

            <div class="text-left mb-5 mt-3 ml-4">
                <!-- Tombol Back -->
                <div class="text-left mt-3 mb-3">
                    <a href="{{ route('seller.keuangan.dashboardKeuangan') }}" class="btn btn-outline kalasewa-color"><i
                            class="fa-solid fa-arrow-left fa-regular me-2"></i>Kembali</a>
                </div>
                <h1 class="fw-bold text-secondary">Pengaturan Rekening</h1>
                <h4 class="fw-semibold text-secondary">Inputkan Informasi Rekening Anda untuk Penarikan</h4>
            </div>

            <div class="row gx-5">

                <div class="card">
                    <div class="card-body">
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        <form action="{{ route('seller.rekening.store') }}" id="submitRekening" method="post">
                            @csrf
                            <div class='form-group'>
                                <label for='tujuan_rek'>Bank/E-Wallet <span class='text-danger'>*</span></label>
                                <select name='tujuan_rek'
                                    class='form-control select2 @error('tujuan_rek') is-invalid @enderror' required>
                                    <option value='' selected disabled>Pilih Bank/E-Wallet</option>
                                    @if ($list_bank)
                                        @foreach ($list_bank as $bank)
                                            <option value="{{ $bank->id }}"
                                                {{ old('tujuan_rek', optional($rekening)->tujuan_rek) == $bank->id ? 'selected' : '' }}>
                                                {{ $bank->nama }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('tujuan_rek')
                                    <div class="text-danger">Bank Tidak Valid / Tidak ada di pilihan</div>
                                @enderror
                            </div>
                            <div class='form-group mb-3'>
                                <label for='nomor_rekening' class='mb-2'>Nomor Rekening Bank/E-Wallet anda <span
                                        class='text-danger'>*</span></label>
                                <input type='number' name='nomor_rekening' class='form-control'
                                    placeholder="Masukan nomor rekening / E-wallet anda"
                                    value="{{ old('nomor_rekening', optional($rekening)->nomor_rekening) }}" required>
                                @error('nomor_rekening')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class='form-group mb-3'>
                                <label for='nama_rekening' class='mb-2'>Atas Nama <span
                                        class='text-danger'>*</span></label>
                                <input type='text' name='nama_rekening' class='form-control'
                                    placeholder="Nama di rekening / E-wallet anda"
                                    value="{{ old('nama_rekening', optional($rekening)->nama_rekening) }}" required>
                                @error('nama_rekening')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="d-grid mt-3">
                                <button type="submit" class="btn btn-danger">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('.select2').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            height: $(this).data('height') ? $(this).data('height') : $(this).hasClass('h-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
        });
    </script>
@endsection
