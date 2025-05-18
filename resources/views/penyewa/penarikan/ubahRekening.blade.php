@extends('layout.template')

@section('content')
    <section class="py-5">
        <div class="container">
            <!-- Header Section -->
            <div class="row mb-4">
                <div class="col-12 text-center">
                    <h1 class="display-4 fw-bold">Ubah Rekening Tujuan</h1>
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
                            <form action="{{ route('ubahRekening') }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label for="bank" class="form-label">Bank/E-Wallet</label>
                                    <select class="form-select form-select-lg" id="bank" name="bank" required>
                                        <option value="">Pilih bank/e-wallet</option>
                                        <option value="BCA">BCA</option>
                                        <option value="Mandiri">Mandiri</option>
                                        <option value="BNI">BNI</option>
                                        <option value="BRI">BRI</option>
                                        <option value="GoPay">GoPay</option>
                                        <option value="OVO">OVO</option>
                                        <option value="DANA">DANA</option>
                                        <option value="LinkAja">LinkAja</option>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label for="nomor_rekening" class="form-label">Nomor Rekening/E-Wallet</label>
                                    <input type="text" class="form-control form-control-lg" id="nomor_rekening"
                                        name="nomor_rekening" placeholder="Masukkan nomor rekening/e-wallet" required>
                                </div>

                                <div class="mb-4">
                                    <label for="nama_pemilik" class="form-label">Nama Pemilik Rekening</label>
                                    <input type="text" class="form-control form-control-lg" id="nama_pemilik"
                                        name="nama_pemilik" placeholder="Masukkan nama pemilik rekening" required>
                                </div>

                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a href="{{ route('penarikan') }}" class="btn btn-outline-secondary btn-lg me-md-2">
                                        <i class="fas fa-times me-2"></i>Batal
                                    </a>
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-save me-2"></i>Simpan Perubahan
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
