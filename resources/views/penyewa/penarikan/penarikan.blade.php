@extends('layout.template')
@extends('layout.navbar')

@section('content')
    <section class="py-5">
        <div class="container">
            <!-- Header Section -->
            <div class="row mb-4">
                <div class="col-12 text-center">
                    <h1 class="display-4 fw-bold">Penarikan Saldo</h1>
                </div>
            </div>

            <!-- Alert Section -->
            <div class="row mb-4">
                <div class="col-12">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Balance Card -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card bg-primary text-white shadow-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h5 class="card-title mb-0">Saldo Tersedia</h5>
                                    <h2 class="display-4 fw-bold mt-2">Rp{{ number_format($saldo, 0, '', '.') }}</h2>
                                </div>
                                <div class="col-md-4 text-md-end">
                                    <a href="{{ route('tarikRekening') }}" class="btn btn-light btn-lg">
                                        <i class="fas fa-money-bill-wave me-2"></i>Tarik Saldo
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- History Section -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h5 class="card-title mb-0">Riwayat Penarikan</h5>
                        </div>
                        <div class="card-body">
                            @if ($penarikan->isNotEmpty())
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Jumlah</th>
                                                <th>No. Rekening</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($penarikan as $item)
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y H:i') }}
                                                    </td>
                                                    <td>Rp{{ number_format($item->jumlah, 0, '', '.') }}</td>
                                                    <td>{{ $item->nomor_rekening }}</td>
                                                    <td>
                                                        @if ($item->status == 'pending')
                                                            <span class="badge bg-warning">Pending</span>
                                                        @elseif ($item->status == 'success')
                                                            <span class="badge bg-success">Success</span>
                                                        @else
                                                            <span class="badge bg-danger">Failed</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-history fa-3x text-muted mb-3"></i>
                                    <p class="lead text-muted">Belum ada riwayat penarikan</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('layout.footer')
@endsection
