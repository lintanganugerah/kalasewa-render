<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>laporan</title>
    <style>
        /* Basic Reset */
        @page {
            size: A4 landscape;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            margin: 0;
            font-weight: 500;
            line-height: 1.2;
        }

        p {
            margin: 0 0 1rem;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        /* Containers */
        .container {
            max-width: 95vh;
            margin: 0 auto;
            padding: 0 15px;
        }

        /* Grid Container */
        .row {
            display: flex;
            flex-wrap: wrap;
            margin-right: -15px;
            margin-left: -15px;
        }

        /* Default Column Width */
        .col {
            flex: 0 0 100%;
            max-width: 100%;
        }

        /* Custom alignment */
        .text-right {
            text-align: right !important;
        }

        /* Typography */
        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        .fw-light {
            font-weight: 300;
        }

        /* Tables */
        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
        }

        .table tbody+tbody {
            border-top: 2px solid #dee2e6;
        }

        .table .table {
            background-color: #fff;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .table-bordered {
            border: 1px solid #dee2e6;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6;
        }

        .highlight {
            background-color: #f9f9f9;
        }

        /* Lists */
        ul {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        ul li {
            margin-bottom: 0.5rem;
        }

        /* Footer */
        .footer {
            text-align: center;
            margin-top: 20px;
        }

        /* Responsive Table */
        .table-responsive {
            display: block;
            width: 100%;
            overflow-x: auto;
        }

        .align-middle {
            vertical-align: middle !important;
            /* Untuk align vertikal di tengah */
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <div class="container" style="margin-top : 40px; margin-bottom : 40px">
        <div class="container">
            <div class="row">
                <div class="col" style="margin-top: 20px; margin-bottom: 20px">
                    <h4>Toko:</h4> {{ auth()->user()->toko->nama_toko }}
                </div>
            </div>
        </div>
        @foreach ($data as $year => $months)
            <hr style="width: 100%;">
            <h2 class="text-center" style="margin-top : 40px; margin-bottom : 40px">Laporan Penghasilan Tahun
                {{ $year }}</h2>
            <hr style="width: 100%;">
            @foreach ($months as $month => $bulan)
                <div class="mt-4" style="margin-top : 20px; margin bottom : 20px">
                    <h3 style="background-color: #f0f0f0; width: 100vw; padding: 10px; margin: 0;">
                        Bulan {{ $month }} Tahun {{ $year }}
                    </h3>
                    <div class="container" style="margin-top: 20px; margin-bottom: 20px;">
                        <div class="row" style="display: flex; justify-content: flex-end; align-items: center;">
                            <div class="col" style="margin-left: 20px;">
                                <h4>Total Order:</h4> {{ $bulan->count() }}
                            </div>
                            <div class="col" style="margin-left: 20px;">
                                <h4>Total Penghasilan:</h4> Rp {{ $bulan->sum('total_penghasilan') }}
                            </div>
                        </div>
                    </div>
                    <table class="table" style="margin-top : 20px; margin bottom : 20px">
                        <thead>
                            <tr>
                                <th>Tanggal Transaksi</th>
                                <th>Produk</th>
                                <th>Harga Sewa</th>
                                <th>Total Additional</th>
                                <th>Fee Admin (5%)</th>
                                <th>Biaya Cuci</th>
                                <th>Ongkir</th>
                                <th>Denda Keterlambatan</th>
                                <th>Denda Lainnya</th>
                                <th>Total Penghasilan Bersih</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bulan as $item)
                                <tr>
                                    <td class="align-middle">{{ $item->created_at }}</td>
                                    <td class="align-middle">{{ $item->produk->nama_produk }}
                                        <small class="fw-light" style="font-size:14px">{{ $item->produk->brand }},
                                            Rp {{ number_format($item->produk->harga, 0, '', '.') }}/3hari, Ukuran
                                            {{ $item->produk->ukuran_produk }}
                                        </small>
                                    </td>
                                    <td class="align-middle">{{ number_format($item->produk->harga, 0, '', '.') }}
                                    </td>
                                    <td class="align-middle">
                                        @if ($item->additional)
                                            <ul>
                                                @foreach ($item->additional as $add)
                                                    <li>{{ $add['nama'] }} +
                                                        {{ number_format($add['harga'], 0, '', '.') }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            Rp {{ number_format(0, 0, '', '.') }}
                                        @endif
                                    </td>
                                    <td class="align-middle">Rp {{ number_format($item->fee_admin, 0, '', '.') }}
                                    </td>
                                    <td class="align-middle">Rp
                                        {{ number_format($item->biaya_cuci ?? 0, 0, '', '.') }}
                                    </td>
                                    <td class="align-middle">Rp
                                        {{ number_format($item->ongkir_pengiriman ?? 0, 0, '', '.') }}</td>
                                    <td class="align-middle">Rp
                                        {{ number_format($item->denda_keterlambatan ?? 0, 0, '', '.') }}</td>
                                    <td class="align-middle">Rp
                                        {{ number_format($item->denda_penyewa ?? 0, 0, '', '.') }}</td>
                                    <td class="align-middle">Rp
                                        {{ number_format($item->total_penghasilan ?? 0, 0, '', '.') }}</td>
                                </tr>
                            @endforeach
                            <tr class="highlight">
                                <td colspan="9" class="text-end">Total Penghasilan Bersih Bulan
                                    {{ $month }} {{ $year }}:
                                </td>
                                <td class="align-middle">Rp {{ $bulan->sum('total_penghasilan') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endforeach
            <div class="page-break"></div>
        @endforeach
    </div>

    <div class="footer mx-4 mt-4">
        <div>&copy; Kalasewa</div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
    </script>
</body>

</html>
