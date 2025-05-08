@extends('layout.template')
@extends('layout.navbar')

@section('content')
    <section>

        <div class="container-fluid">
            <div class="container">
                <div class="header-text text-center mt-5">
                    <h1><strong>Ubah Tujuan Rekening</strong></h1>
                </div>

                <div class="alert-content mt-2">
                    @csrf
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            {{ $errors->first() }}
                        </div>
                    @endif
                </div>

                <div class="table-penarikan mt-3">
                    <div class="card">
                        <div class="card-body">

                            <form action="{{ route('setRekening') }}" method="post">
                                @csrf
                                <div class="bank-wallet">
                                    <label for="exampleInputEmail1" class="form-label">Bank/E-Wallet<span class="text-danger">*</span></label>
                                    <select class="form-select form-control-lg select2" id="selectRekening" aria-label="Default select example" name='tujuan_rek' required>
                                        <option></option>
                                        @foreach ($rekenings as $rekening)
                                            <option value="{{ $rekening->id }}">{{ $rekening->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div id="emailHelp" class="form-text">Silahkan pilih Bank
                                        atau E-Wallet yang anda gunakan</div>
                                </div>

                                <div class="nomor-rekening mt-2">
                                    <label for="exampleInputEmail1" class="form-label">Nomor
                                        Rekening Bank/E-Wallet<span class="text-danger">*</span></label>
                                    <input class="form-control form-control-lg" type="number" name='nomor_rekening' required>
                                    <div id="emailHelp" class="form-text">Silahkan masukkan
                                        nomor rekening/e-wallet anda</div>
                                </div>

                                <div class="atas-nama mt-2">
                                    <label for="exampleInputEmail1" class="form-label">Atas
                                        Nama<span class="text-danger">*</span></label>
                                    <input class="form-control form-control-lg" type="text" name='nama_rekening' required>
                                    <div id="emailHelp" class="form-text">Silahkan masukkan
                                        nama yang terdaftar di Rekening/E-Wallet anda</div>
                                </div>

                                <div class="aksi-btn d-flex gap-3 mt-3">
                                    <a href="{{ route('viewPenarikan') }}" class="btn btn-danger w-100">Batal</a>
                                    <button type="submit" class="btn btn-primary w-100">Simpan Perubahan</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('layout.footer')
    </section>
@endsection
