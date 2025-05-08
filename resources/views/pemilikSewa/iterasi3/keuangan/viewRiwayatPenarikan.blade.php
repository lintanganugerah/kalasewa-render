@extends('layout.selllayout')
@section('title', 'Dashboard')

@section('content')
    <div class="row">
        <div class="col">
            <div class="d-flex justify-content-between mb-3 mt-3">
                <div>
                    <h1 class="fw-bold text-secondary">Dashboard Keuangan</h1>
                    <p class="fw-semibold text-secondary">Lihat Riwayat, saldo dan tarik penghasilan anda disini</p>
                </div>
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
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card" style="overflow: visible!important;">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md">
                            <h4>Informasi Keuangan Anda</h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="d-flex">
                                <p class="small mr-2">Total Penghasilan</p>
                                <div class="dropdown no-arrow small">
                                    <a class="dropdown-toggle" id="dropdownMenuLink" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <span id="dropdown-text">Bulan Ini</span> <i class="fas fa-chevron-down"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                        aria-labelledby="dropdownMenuLink">
                                        <div class="dropdown-header">Select Periode</div>
                                        <a class="dropdown-item bulan-select" data-periode="0"
                                            id="Januari-bulan">Januari</a>
                                        <a class="dropdown-item bulan-select" data-periode="1"
                                            id="Februari-bulan">Februari</a>
                                        <a class="dropdown-item bulan-select" data-periode="2" id="Maret-bulan">Maret</a>
                                        <a class="dropdown-item bulan-select" data-periode="3" id="April-bulan">April</a>
                                        <a class="dropdown-item bulan-select" data-periode="4" id="Mei-bulan">Mei</a>
                                        <a class="dropdown-item bulan-select" data-periode="5" id="Juni-bulan">Juni</a>
                                        <a class="dropdown-item bulan-select" data-periode="6" id="Juli-bulan">Juli</a>
                                        <a class="dropdown-item bulan-select" data-periode="7"
                                            id="Agustus-bulan">Agustus</a>
                                        <a class="dropdown-item bulan-select" data-periode="8"
                                            id="September-bulan">September</a>
                                        <a class="dropdown-item bulan-select" data-periode="9"
                                            id="Oktober-bulan">Oktober</a>
                                        <a class="dropdown-item bulan-select" data-periode="10"
                                            id="November-bulan">November</a>
                                        <a class="dropdown-item bulan-select" data-periode="11"
                                            id="Desember-bulan">Desember</a>
                                    </div>
                                </div>
                            </div>
                            <h5 class="info-keuangan" id="penghasilan-bulan">
                                @include('pemilikSewa.iterasi3.keuangan.penghasilan', [
                                    'penghasilanBulan' => $penghasilanBulan,
                                ])
                            </h5>
                        </div>
                        <div class="col">
                            <p class="small">Transaksi Berjalan</p>
                            <h5 class="info-keuangan">
                                Rp. {{ number_format(auth()->user()->toko->saldo_tertunda(), 0, ',', '.') }}
                            </h5>
                        </div>
                        <div class="col">
                            <p class="small">Saldo dapat ditarik</p>
                            <h5 class="info-keuangan">
                                Rp.
                                {{ number_format(auth()->user()->saldo_user->saldo ?? '0', 0, ',', '.') }}
                            </h5>
                        </div>
                        <div class="col">
                            <p class="small">Rekening Penarikan Anda</p>
                            <h5 class="info-keuangan">
                                @if (auth()->user()->saldo_user->nomor_rekening ?? false)
                                    {{ auth()->user()->saldo_user->tujuanRekening->nama }} :
                                    {{ auth()->user()->saldo_user->nomor_rekening }}
                                @else
                                    xxx : xxxxxxxx
                                @endif
                                <a href="{{ route('seller.rekening.viewSetRekening') }}" class="small ml-2 btnRekening">
                                    <i class="fas fa-pencil"></i>
                                </a>
                            </h5>
                        </div>
                        <div class="col align-self-center">
                            <a href="{{ route('seller.penarikan.viewTarikSaldo') }}" class="btn btn-danger">Tarik Saldo</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                    <li class="list-inline-item">
                        <a href="{{ route('seller.keuangan.dashboardKeuangan') }}"
                            class="nav-link text-secondary">Penghasilan
                            dari pesanan</a>
                    </li>
                    <li class="list-inline-item">
                        <a href="{{ route('seller.penarikan.viewRiwayatPenarikan') }}"
                            class="nav-link active text-secondary fw-bold">Riwayat
                            Penarikan</a>
                    </li>
                </ul>
            </div>
            <div class="card">
                <div class="card-body">
                    <table class="table no-more-tables tabel-data" id="series-table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Jumlah</th>
                                <th>Rekening/Nomor Anda</th>
                                <th>Atas Nama Rekening</th>
                                <th>Bank/E-Wallet</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="series-table">
                            @foreach ($data_penarikan as $penarikan)
                                <tr>
                                    <td data-title="Tanggal" class="align-middle">
                                        {{ $penarikan->created_at->translatedFormat('d/m/Y') }}</td>
                                    <td data-title="Jumlah" class="align-middle">Rp.
                                        {{ number_format($penarikan->nominal, 0, ',', '.') }}</td>
                                    <td data-title="Rekening" class="align-middle">{{ $penarikan->nomor_rekening }}</td>
                                    <td data-title="Atas Nama Rek." class="align-middle">{{ $penarikan->nama_rekening }}
                                    </td>
                                    <td data-title="Tujuan" class="align-middle">{{ $penarikan->bank }}</td>
                                    <td data-title="Status" class="align-middle">{{ $penarikan->status }}
                                        @if ($penarikan->status == 'Ditolak')
                                            karena {{ $penarikan->alasan_penolakan }}
                                        @elseif ($penarikan->status == 'Selesai')
                                            <br><a href="{{ asset($penarikan->bukti_transfer) }}" target="_blank">Lihat
                                                Bukti
                                                Transfer</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- <div style="display: flex; justify-content: center; margin: 20px 0;">
                            {{ $items->onEachSide(1)->links() }}
                        </div> --}}
                </div>
            </div>
        </div>
    </div>
    <script>
        $('.tabel-data').DataTable({
            lengthMenu: [
                [5, 10, 25, -1],
                [5, 10, 25, 'All']
            ],
        });

        function toggleModal() {
            const namaproduk = event.srcElement.getAttribute('data-namaproduk');
            const dendapenyewa = event.srcElement.getAttribute('data-dendapenyewa');
            const hargaproduk = event.srcElement.getAttribute('data-hargaproduk');
            const additional = event.srcElement.getAttribute('data-additional');
            const biayacuci = event.srcElement.getAttribute('data-biayacuci');
            const ongkir = event.srcElement.getAttribute('data-ongkir');
            const feeadmin = event.srcElement.getAttribute('data-feeadmin');
            const totalpenghasilan = event.srcElement.getAttribute('data-totalpenghasilan');
            const totalharga = event.srcElement.getAttribute('data-totalharga');
            const formatter = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            });

            document.getElementById('modal_namaproduk').textContent = namaproduk;
            document.getElementById('modal_dendapenyewa').textContent = formatter.format(dendapenyewa)
            document.getElementById('modal_hargaproduk').textContent = formatter.format(hargaproduk);
            document.getElementById('modal_additional').textContent = formatter.format(additional);
            document.getElementById('modal_biayacuci').textContent = formatter.format(biayacuci);
            document.getElementById('modal_feeadmin').textContent = formatter.format(feeadmin);
            document.getElementById('modal_totalpenghasilan').textContent = formatter.format(totalpenghasilan);
            document.getElementById('modal_totalharga').textContent = formatter.format(totalharga);
            if (ongkir == "Belum anda inputkan") {
                document.getElementById('modal_ongkir').textContent = "Belum anda masukan biaya pengiriman";
            } else {
                document.getElementById('modal_ongkir').textContent = formatter.format(ongkir);
            }
            $('#modalDetailPenghasilan').modal('show');

            // if (bodyCardDetail.classList.contains('collapse') && bodyCardDetail.classList.contains('show')) {
            //     bodyCardDetail.classList.remove('show');
            //     bodyCardDetail.classList.add('collapsing');
            //     icon.classList.add('fa-chevron-down');
            //     icon.classList.remove('fa-chevron-up');
            // } else if (bodyCardDetail.classList.contains('collapsing')) {
            //     bodyCardDetail.classList.remove('collapsing');
            //     bodyCardDetail.classList.add('collapse');
            //     bodyCardDetail.classList.add('show');
            //     icon.classList.remove('fa-chevron-down');
            //     icon.classList.add('fa-chevron-up');
            // }
        }

        console.log('masuk')
        var bulan = [
            "Januari", "Februari", "Maret", "April", "Mei", "Juni",
            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
        ];

        var now = new Date();
        var bulanSekarang = bulan[now.getMonth()]; //Ambil bulan sesuai dengan index bulan yang didapat skrg
        var dropdownItems = document.querySelectorAll('.dropdown-item.bulan-select');
        var dropdownTeks = document.getElementById('dropdown-text');

        dropdownItems.forEach(function(item) {
            if (bulan[item.dataset.periode] == bulanSekarang) {
                dropdownTeks.textContent = bulan[item.dataset.periode];
                item.classList.add('active');
            }
        });
        $('.bulan-select').on('click', function(e) {
            var periode = $(this).data('periode');
            var url = `/keuangan-bulan/${periode}`;

            // Ubah class active pada dropdown
            $('.bulan-select').removeClass('active');
            $(this).addClass('active');

            // Ubah teks dropdown
            $('#dropdown-text').text(bulan[periode]);

            // Ambil data dari controller
            $.ajax({
                url: url,
                method: 'GET',
                success: function(response) {
                    console.log(response);
                    $('#penghasilan-bulan').html(response);
                },
                error: function(xhr, status, error) {
                    console.log('error');
                    console.error('AJAX Error:', status, error);
                }
            });
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection
