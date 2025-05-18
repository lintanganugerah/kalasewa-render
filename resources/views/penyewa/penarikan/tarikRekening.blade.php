@extends('layout.template')

@section('content')
    <section class="py-5">
        <div class="container">
            <!-- Header Section -->
            <div class="row mb-4">
                <div class="col-12 text-center">
                    <h1 class="display-4 fw-bold">Tarik Saldo</h1>
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

            <!-- Main Content -->
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            <form action="{{ route('tarikRekening') }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label for="jumlah" class="form-label">Jumlah Penarikan</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control form-control-lg" id="jumlah"
                                            name="jumlah" placeholder="Masukkan jumlah penarikan" required>
                                    </div>
                                    <div class="form-text">Saldo tersedia: Rp{{ number_format($saldo, 0, '', '.') }}</div>
                                </div>

                                <div class="mb-4">
                                    <label for="tujuan_rekening" class="form-label">Tujuan Rekening</label>
                                    <select class="form-select form-select-lg" id="tujuan_rekening" name="tujuan_rekening"
                                        required>
                                        <option value="">Pilih rekening tujuan</option>
                                        @foreach ($rekening as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->nama_bank }} - {{ $item->nomor_rekening }}
                                                ({{ $item->nama_pemilik }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a href="{{ route('penarikan') }}" class="btn btn-outline-secondary btn-lg me-md-2">
                                        <i class="fas fa-times me-2"></i>Batal
                                    </a>
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-money-bill-wave me-2"></i>Tarik Saldo
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
