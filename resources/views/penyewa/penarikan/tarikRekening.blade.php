@extends('layout.template')
@extends('layout.navbar')

@section('content')
    <section>

        <div class="container-fluid">
            <div class="container">
                <div class="header-text text-center mt-5">
                    <h1><strong>Penarikan Saldo</strong></h1>
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

                            <form action="{{ route('tarikSaldo') }}" method="post">
                                @csrf
                                <div class="nominal-tarik">
                                    <label for="exampleInputEmail1" class="form-label">Nominal<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon1">Rp</span>
                                        <input type="number" class="form-control form-control-lg" name="nominal" placeholder="Nominal" aria-label="Nominal"
                                            aria-describedby="basic-addon1" value="{{ $saldos->saldo ?? 0 }}" disabled>
                                    </div>
                                    <div id="emailHelp" class="form-text">Nominal yang dapat anda tarik</div>
                                </div>

                                <div class="tujuang-rekening mt-3">
                                    <label for="exampleInputEmail1" class="form-label">Tujuang Rekening<span class="text-danger">*</span></label>
                                    <input class="form-control form-control-lg" type="text" placeholder=".form-control-lg" aria-label=".form-control-lg example"
                                        name="tujuan_rekening" value="{{ $saldos->TujuanRekening->nama }} | {{ $saldos->nomor_rekening }} | {{ $saldos->nama_rekening }}" disabled>
                                    <div id="emailHelp" class="form-text">Pastikan nomor rekening/e-wallet benar. Ingin
                                        mengubah tujuan transfer? <a href="{{ route('viewUbahRekening') }}" class="text-danger">Klik disini</a></div>
                                </div>

                                <div class="aksi-btn d-flex gap-3 mt-3">
                                    <a href="{{ route('viewPenarikan') }}" class="btn btn-danger w-100">Batal</a>
                                    <button type="submit" class="btn btn-primary w-100">Tarik Saldo</button>
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
