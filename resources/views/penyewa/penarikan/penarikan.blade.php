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
                            <div class="info-saldo">
                                <h3 class="fw-bold">Informasi Keuangan</h3>
                            </div>
                            <div class="row mt-3">
                                <div class="col-4">
                                    <p>Saldo Dapat Ditarik</p>
                                    @if ($saldos)
                                        <h3><strong>Rp{{ number_format($saldos->saldo, 0, '', '.') }}</strong></h3>
                                    @else
                                        <h3><strong>Rp0</strong></h3>
                                    @endif
                                </div>
                                <div class="col-4">
                                    <p>Rekening Penarikan</p>
                                    @if ($saldos)
                                        <h3><strong>{{ $saldos->tujuanRekening->nama ?? '***' }}</strong></h3>
                                        <h3><strong>{{ $saldos->nomor_rekening ?? ' XXXXXXXXXXX' }}</strong></h3>
                                    @else
                                        <h3><strong>xxxxxxxxx</strong></h3>
                                    @endif
                                    <a href="{{ route('viewUbahRekening') }}" class="btn btn-outline-primary w-100">Ubah
                                        Tujuan Rekening</a>
                                </div>
                                <div class="col-4 text-end">
                                    <a href="{{ route('viewTarikRekening') }}" class="btn btn-danger">Tarik Saldo</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="info-saldo">
                                <h3 class="fw-bold mb-3">Riwayat Penarikan</h3>
                            </div>
                            <table class="table w-100" id="table-penarikan">
                                <thead>
                                    <tr>
                                        <td class="fw-bold">#</td>
                                        <td class="fw-bold">Tanggal</td>
                                        <td class="fw-bold">Jumlah</td>
                                        <td class="fw-bold">Nomor Rekening/E-Wallet</td>
                                        <td class="fw-bold">Atas Nama</td>
                                        <td class="fw-bold">Bank/E-Wallet</td>
                                        <td class="fw-bold">Status</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($penarikans as $penarikan)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $penarikan->created_at }}</td>
                                            <td>{{ $penarikan->nominal }}</td>
                                            <td>{{ $penarikan->nomor_rekening }}</td>
                                            <td>{{ $penarikan->nama_rekening }}</td>
                                            <td>{{ $penarikan->bank }}</td>
                                            <td>{{ $penarikan->status }}
                                                @if ($penarikan->status == 'Ditolak')
                                                    karena {{ $penarikan->alasan_penolakan }}
                                                @elseif ($penarikan->status == 'Selesai')
                                                    <br><a href="{{ asset($penarikan->bukti_transfer) }}"
                                                        target="_blank">Lihat
                                                        Bukti
                                                        Transfer</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('layout.footer')

    </section>
@endsection
