@extends('admin.layout.app')

@section('title', 'Dashboard')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
        </ol>
    </div>

    <div class="row mb-3">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Pendapatan Platform</div>
                            <div class="h2 mt-2 font-weight-bold text-gray-800">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
                            <div class="mt-2 mb-0 text-muted text-xs">
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-coins fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Penyewa Terdaftar</div>
                            <div class="h2 mt-2 mr-3 font-weight-bold text-gray-800">{{ $totalPenyewaTerdaftar }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Pemilik Sewa Terdaftar</div>
                            <div class="h2 mt-2 mr-3 font-weight-bold text-gray-800">{{ $totalPemilikSewaTerdaftar }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Pending Ticket</div>
                            <div class="h2 mt-2 mr-3 font-weight-bold text-gray-800">{{ $totalPendingTicket }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Pending Refund (Penyewa)</div>
                            <div class="h2 mt-2 mr-3 font-weight-bold text-gray-800">{{ $totalPendingFundPenyewa }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comment-dollar fa-2x text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Pending Withdraw (Toko)</div>
                            <div class="h2 mt-2 mr-3 font-weight-bold text-gray-800">{{ $totalPendingFundPemilik }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-credit-card fa-2x text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Pending Verifikasi</div>
                            <div class="h2 mt-2 mr-3 font-weight-bold text-gray-800">{{ $totalPendingVerifikasi }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100">
              <div class="card-body">
                <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-uppercase mb-1">Pending Retur</div>
                    <div class="h2 mt-2 mr-3 font-weight-bold text-gray-800">{{ $totalPendingRetur }}</div>
                  </div>
                  <div class="col-auto">
                    <i class="fas fa-boxes fa-2x text-danger"></i>
                  </div>
                </div>
              </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5">
            <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Paling Diminati</h6>
                </div>
                <div class="card-body">
                    @php
                        $tiers = ['Tier 1', 'Tier 2', 'Tier 3'];
                    @endphp
                    @foreach ($tiers as $index => $tier)
                        @php
                            $series = $topSeries->get($index);
                        @endphp
                        <div class="mb-3">
                            <div class="large text-black">
                                {{ $series ? $series->series->series : "$tier belum ada series diminati" }}
                                <div class="large float-right">
                                    <b>{{ $series ? $series->banyak_dipesan . 'x Disewa' : '' }}</b>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
