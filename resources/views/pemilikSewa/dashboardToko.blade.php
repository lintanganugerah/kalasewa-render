@extends('layout.selllayout')
@section('content')
    <div class="row">
        <div class="col">
            <div id="wrapper">
                <div id="content-wrapper" class="d-flex flex-column">
                    <div id="content">
                        <!-- Container Fluid-->
                        <div class="container-fluid" id="container-wrapper">
                            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                                <h1 class="h3 mb-0 fs-1">Dashboard</h1>
                            </div>

                            <div class="d-sm-flex align-items-center justify-content-between mb-3">
                                <h4 class="h3 mb-0">Status Penyewaan</h4>
                            </div>
                            <div class="row mb-3">
                                <!-- Card Belum diproses -->
                                <div class="col-xl-3 col-md-6 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col mr-2">
                                                    <div class="text-xs font-weight-bold text-uppercase mb-1">
                                                        Menunggu diproses</div>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                        {{ auth()->user()->toko->countPenyewaan('Menunggu di Proses') }}
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <i class="fa-solid fa-hourglass-start text-warning fa-2x"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer text-center">
                                            <a class="m-0 small text-primary card-link"
                                                href="{{ route('seller.statuspenyewaan.belumdiproses') }}">View More <i
                                                    class="fas fa-chevron-right"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Card Sedang berlangsung -->
                                <div class="col-xl-3 col-md-6 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="row no-gutters align-items-center">
                                                <div class="col mr-2">
                                                    <div class="text-xs font-weight-bold text-uppercase mb-1">
                                                        Sedang Berlangsung
                                                    </div>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                        {{ auth()->user()->toko->countPenyewaan('Sedang Berlangsung') }}
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <i class="fa-solid fa-calendar-day fa-2x text-primary"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer text-center">
                                            <a class="m-0 small text-primary card-link"
                                                href="{{ route('seller.statuspenyewaan.sedangberlangsung') }}">View More <i
                                                    class="fas fa-chevron-right"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Card menunggu konfirmasi selesai -->
                                <div class="col-xl-3 col-md-6 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="row no-gutters align-items-center">
                                                <div class="col mr-2">
                                                    <div class="text-xs font-weight-bold text-uppercase mb-1">
                                                        Menunggu konfirmasi selesai
                                                    </div>
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                                        {{ auth()->user()->toko->countPenyewaan('Telah Kembali') }}</div>
                                                </div>
                                                <div class="col-auto">
                                                    <i class="fa-regular fa-circle-check fa-2x text-success"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer text-center">
                                            <a class="m-0 small text-primary card-link"
                                                href="{{ route('seller.statuspenyewaan.telahkembali') }}">View More <i
                                                    class="fas fa-chevron-right"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Card menunggu konfirmasi diretur -->
                                <div class="col-xl-3 col-md-6 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="row no-gutters align-items-center">
                                                <div class="col mr-2">
                                                    <div class="text-xs font-weight-bold text-uppercase mb-1">
                                                        Menunggu konfirmasi diretur</div>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                        {{ auth()->user()->toko->countPenyewaan('Retur dalam Pengiriman') }}
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <i class="fa-solid fa-receipt fa-2x text-danger"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer text-center">
                                            <a class="m-0 small text-primary card-link"
                                                href="{{ route('seller.statuspenyewaan.penyewaandiretur') }}">View More <i
                                                    class="fas fa-chevron-right"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-sm-flex align-items-center justify-content-between mb-3">
                                    <h4 class="h3 mb-0">Performa Toko</h4>
                                </div>
                                <!-- Penghasilan -->
                                <div class="col-xl-3 col-md-6 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="row no-gutters align-items-center">
                                                <div class="col mr-2">
                                                    <div class="text-xs font-weight-bold text-uppercase mb-1">
                                                        Penghasilan Bulan ini</div>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800">Rp.
                                                        {{ number_format(auth()->user()->toko->penghasilan_bulan_ini(), 0, ',', '.') }}
                                                    </div>
                                                    <div class="mt-2 mb-0 text-muted text-xs">
                                                        <span><a class="m-0 text-primary card-link"
                                                                href="{{ route('seller.keuangan.dashboardKeuangan') }}">View
                                                                More
                                                                <i class="fas fa-chevron-right"></i></a></span>
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <i class="fa-solid fa-money-bill-wave fa-2x text-danger"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Total penyewaan semua status -->
                                <div class="col-xl-3 col-md-6 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="row no-gutters align-items-center">
                                                <div class="col mr-2">
                                                    <div class="text-xs font-weight-bold text-uppercase mb-1">Total
                                                        Order Penyewaan</div>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                        {{ auth()->user()->toko->countPenyewaanAll()[0] }}</div>
                                                    <div class="mt-2 mb-0 text-muted text-xs">
                                                        <span class="mr-2">
                                                            @if (auth()->user()->toko->countPenyewaanAll()[2] == 'naik')
                                                                <i class="fas fa-arrow-up text-success"></i>
                                                                <span
                                                                    class="text-success">{{ number_format(auth()->user()->toko->countPenyewaanAll()[1], 0) }}%</span>
                                                            @elseif(auth()->user()->toko->countPenyewaanAll()[2] == 'turun')
                                                                <i class="fas fa-arrow-down text-danger"></i>
                                                                <span
                                                                    class="text-danger">{{ number_format(auth()->user()->toko->countPenyewaanAll()[1], 0) }}%</span>
                                                            @else
                                                                <i class="fas fa-minus text-secondary"></i>
                                                                <span
                                                                    class="text-secondary">{{ auth()->user()->toko->countPenyewaanAll()[1] }}%</span>
                                                            @endif
                                                        </span>
                                                        <span>Dari bulan lalu
                                                            ({{ auth()->user()->toko->countPenyewaanAll()[3] }}
                                                            penyewaan)</span>
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <i class="fa-solid fa-cart-shopping fa-2x"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Pending Requests Card Example -->
                                <div class="col-xl-3 col-md-6 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="row no-gutters align-items-center">
                                                <div class="col mr-2">
                                                    <div class="text-xs font-weight-bold text-uppercase mb-1">Penyewaan
                                                        Selesai</div>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                        {{ auth()->user()->toko->countPenyewaanAllBerstatus('Penyewaan Selesai')[0] }}
                                                    </div>
                                                    <div class="mt-2 mb-0 text-muted text-xs">
                                                        <span class="mr-2">
                                                            @if (auth()->user()->toko->countPenyewaanAllBerstatus('Penyewaan Selesai')[2] == 'naik')
                                                                <i class="fas fa-arrow-up text-success"></i>
                                                                <span
                                                                    class="text-success">{{ number_format(auth()->user()->toko->countPenyewaanAllBerstatus('Penyewaan Selesai')[1], 0) }}%</span>
                                                            @elseif(auth()->user()->toko->countPenyewaanAllBerstatus('Penyewaan Selesai')[2] == 'turun')
                                                                <i class="fas fa-arrow-down text-danger"></i>
                                                                <span
                                                                    class="text-danger">{{ number_format(auth()->user()->toko->countPenyewaanAllBerstatus('Penyewaan Selesai')[1], 0) }}%</span>
                                                            @else
                                                                <i class="fas fa-minus text-secondary"></i>
                                                                <span
                                                                    class="text-secondary">{{ auth()->user()->toko->countPenyewaanAllBerstatus('Penyewaan Selesai')[1] }}%</span>
                                                            @endif
                                                        </span>
                                                        <span>Dari bulan lalu
                                                            ({{ auth()->user()->toko->countPenyewaanAllBerstatus('Penyewaan Selesai')[3] }}
                                                            penyewaan)</span>
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <i class="fa-solid fa-clipboard-check fa-2x text-info"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Pending Requests Card Example -->
                                <div class="col-xl-3 col-md-6 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="row no-gutters align-items-center">
                                                <div class="col mr-2">
                                                    <div class="text-xs font-weight-bold text-uppercase mb-1">Penyewaan
                                                        Diretur</div>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                        {{ auth()->user()->toko->countPenyewaanAllBerstatus('ReturAll')[0] }}
                                                    </div>
                                                    <div class="mt-2 mb-0 text-muted text-xs">
                                                        <span class="mr-2">
                                                            @if (auth()->user()->toko->countPenyewaanAllBerstatus('ReturAll')[2] == 'naik')
                                                                <i class="fas fa-arrow-up text-danger"></i>
                                                                <span
                                                                    class="text-danger">{{ number_format(auth()->user()->toko->countPenyewaanAllBerstatus('ReturAll')[1], 0) }}%</span>
                                                            @elseif(auth()->user()->toko->countPenyewaanAllBerstatus('ReturAll')[2] == 'turun')
                                                                <i class="fas fa-arrow-down text-success"></i>
                                                                <span
                                                                    class="text-success">{{ number_format(auth()->user()->toko->countPenyewaanAllBerstatus('ReturAll')[1], 0) }}%</span>
                                                            @else
                                                                <i class="fas fa-minus text-secondary"></i>
                                                                <span
                                                                    class="text-secondary">{{ auth()->user()->toko->countPenyewaanAllBerstatus('ReturAll')[1] }}%</span>
                                                            @endif
                                                        </span>
                                                        <span>Dari bulan lalu
                                                            ({{ auth()->user()->toko->countPenyewaanAllBerstatus('ReturAll')[3] }}
                                                            penyewaan)</span>
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <i class="fas fa-boxes-packing fa-2x text-warning"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-sm-flex align-items-center justify-content-between mb-3">
                                    <h4 class="h3 mb-0">Lainnya</h4>
                                </div>
                                <!-- Pie Chart -->
                                <div class="col-xl-4 col-lg-5">
                                    <div class="card mb-4">
                                        <div
                                            class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                            <div>
                                                <h6 class="m-0 font-weight-bold" style="color: #CE2525">
                                                    Produk terlaris anda</h6>
                                                <span class="m-0 small text-secondary card-link"
                                                    id="teksdeskripsi-produk">(Bulan
                                                    ini)</span>
                                            </div>
                                            <div class="dropdown no-arrow">
                                                <a class="dropdown-toggle btn btn-kalasewa-dark btn-sm" href="#"
                                                    role="button" id="dropdownMenuLink" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    <span id="dropdown-text-produk">Bulan Ini</span> <i
                                                        class="fas fa-chevron-down"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                                    aria-labelledby="dropdownMenuLink">
                                                    <div class="dropdown-header">Select Periode</div>
                                                    <a class="dropdown-item produk-select active" data-periode="bulan_ini"
                                                        id="bulanini-series">Bulan Ini</a>
                                                    <a class="dropdown-item produk-select" data-periode="bulan_kemarin"
                                                        id="bulankemarin-series">Bulan
                                                        Kemarin</a>
                                                    <a class="dropdown-item produk-select" data-periode="tahun_ini"
                                                        id="alltime-series">Tahun Ini</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body" id="top-produk-body">
                                            @include('pemilikSewa.iterasi3.topproduk_tabel', [
                                                'topproduk' => $topproduk,
                                            ])
                                        </div>
                                    </div>
                                </div>
                                <!-- Series -->
                                <div class="col-xl-4 col-lg-5">
                                    <div class="card mb-4">
                                        <div
                                            class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                            <div>
                                                <h6 class="m-0 font-weight-bold" style="color: #CE2525">
                                                    Top series pada website</h6>
                                                <span class="m-0 small text-secondary card-link" id="teksdeskripsi">(Bulan
                                                    ini)</span>
                                            </div>
                                            <div class="dropdown no-arrow">
                                                <a class="dropdown-toggle btn btn-kalasewa-dark btn-sm" href="#"
                                                    role="button" id="dropdownMenuLink" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    <span id="dropdown-text">Bulan Ini</span> <i
                                                        class="fas fa-chevron-down"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                                    aria-labelledby="dropdownMenuLink">
                                                    <div class="dropdown-header">Select Periode</div>
                                                    <a class="dropdown-item series-select active" data-periode="bulan_ini"
                                                        id="bulanini-series">Bulan Ini</a>
                                                    <a class="dropdown-item series-select" data-periode="bulan_kemarin"
                                                        id="bulankemarin-series">Bulan
                                                        Kemarin</a>
                                                    <a class="dropdown-item series-select" data-periode="tahun_ini"
                                                        id="alltime-series">Tahun Ini</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body" id="body-topseries">
                                            @include('pemilikSewa.iterasi3.topseries_tabel', [
                                                'topseries' => $topseries,
                                            ])
                                        </div>
                                    </div>
                                </div>
                                <!-- Message From Customer-->
                                <div class="col-xl-4 col-lg-5 ">
                                    <div class="card">
                                        <div
                                            class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                            <div>
                                                <h6 class="m-0 font-weight-bold" style="color: #CE2525">
                                                    Chat</h6>
                                                <span class="m-0 small text-secondary card-link" href="#">(Pesan
                                                    belum dibaca)</span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            @if ($chat_belum_dibaca->isNotEmpty())
                                                @foreach ($chat_belum_dibaca as $idPengirim => $chat)
                                                    <a href="{{ url('/chat' . '/' . $idPengirim) }}" target="_blank"
                                                        class="customer-message mb-3 d-flex flex-row align-items-center justify-content-between text-decoration-none">
                                                        <div>
                                                            <div class="cut-text message-title text-gray-800">
                                                                "{{ $chat->first()->body }}"
                                                            </div>
                                                            <span
                                                                class="small text-gray-500 message-time font-weight-bold">{{ $chat->first()->userPengirim->nama }}</span>
                                                            <span
                                                                class="small text-gray-500 message-time font-weight-bold">¡¤
                                                                {{ $chat->first()->time() }}</span>
                                                        </div>
                                                        <div class="d-flex align-items-center">
                                                            <span class="badge rounded-pill bg-danger mr-3">
                                                                {{ $chat->count() }}
                                                                <span class="visually-hidden">unread messages</span>
                                                            </span>
                                                            <i class="fas fa-chevron-right text-danger"></i>
                                                        </div>
                                                    </a>
                                                @endforeach
                                            @else
                                                <p> Tidak ada </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('.series-select').on('click', function(e) {
            var periode = $(this).data('periode');
            var url = `/top-series/${periode}`;

            // var tahun = new Date().getFullYear();
            // var bulan_kemarin = new Date(tahun, new Date().getMonth() - 1, 1)
            // var bulan_kemarin_formatted = new Intl.DateTimeFormat('id-ID', {
            //     month: "long"
            // }).format(bulan_kemarin);
            // var bulan_ini = new Date(tahun, new Date().getMonth(), 1)
            // var bulan_ini_formatted = new Intl.DateTimeFormat('id-ID', {
            //     month: "long"
            // }).format(bulan_ini);

            // Ubah class active pada dropdown
            $('.series-select').removeClass('active');
            $(this).addClass('active');

            // Ubah teks dropdown
            var dropdownText = $(this).text();
            $('#dropdown-text').text(dropdownText);
            var deskripsi = {
                'bulan_ini': '(Bulan Ini)',
                'bulan_kemarin': '(Bulan kemarin)',
                'tahun_ini': '(Tahun Ini)',
            }; // Simpan Deskripsi dalam key : value
            $('#teksdeskripsi').text(deskripsi[
                periode]); // Ambil isi teks deskripsinya berdasarkan key dari periode

            // Fetch new content
            $.ajax({
                url: url,
                method: 'GET',
                success: function(response) {
                    console.log(response);
                    $('#body-topseries').html(response);
                },
                error: function(xhr, status, error) {
                    console.log('error');
                    console.error('AJAX Error:', status, error);
                }
            });
        });
        $('.produk-select').on('click', function(e) {
            var periode = $(this).data('periode');
            var url = `/top-produk/${periode}`;

            // var tahun = new Date().getFullYear();
            // var bulan_kemarin = new Date(tahun, new Date().getMonth() - 1, 1)
            // var bulan_kemarin_formatted = new Intl.DateTimeFormat('id-ID', {
            //     month: "long"
            // }).format(bulan_kemarin);
            // var bulan_ini = new Date(tahun, new Date().getMonth(), 1)
            // var bulan_ini_formatted = new Intl.DateTimeFormat('id-ID', {
            //     month: "long"
            // }).format(bulan_ini);

            // Update active class for dropdown items
            $('.produk-select').removeClass('active');
            $(this).addClass('active');

            // Update dropdown text and description
            var dropdownText = $(this).text();
            $('#dropdown-text-produk').text(dropdownText);
            var deskripsi = {
                'bulan_ini': '(Bulan Ini)',
                'bulan_kemarin': '(Bulan kemarin)',
                'tahun_ini': '(Tahun Ini)',
            }; // Simpan Deskripsi dalam key : value
            $('#teksdeskripsi-produk').text(deskripsi[
                periode]); // Ambil isi teks deskripsinya berdasarkan key dari periode

            // Fetch new content
            $.ajax({
                url: url,
                method: 'GET',
                success: function(response) {
                    console.log(response);
                    $('#top-produk-body').html(response);
                },
                error: function(xhr, status, error) {
                    console.log('error');
                    console.error('AJAX Error:', status, error);
                }
            });
        });
    </script>
@endsection
<link type="text/css" href="{{ asset('seller/review.css') }}" rel="stylesheet">