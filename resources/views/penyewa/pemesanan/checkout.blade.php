@extends('layout.template')

@section('content')
    <section class="py-5">
        <div class="container">
            <!-- Header Section -->
            <div class="row mb-4">
                <div class="col-12 text-center">
                    <h1 class="display-4 fw-bold">Checkout Pesanan Kamu!</h1>
                </div>
            </div>

            <!-- Alert Section -->
            <div class="row mb-4">
                <div class="col-12">
                    @csrf
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
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            @if ($checkout->isNotEmpty())
                                @foreach ($checkout as $item)
                                    <div class="row g-4 mb-4">
                                        <!-- Product Image -->
                                        <div class="col-md-2">
                                            <img src="{{ asset($item->produk->fotoProduk->path) }}" alt="Catalog"
                                                class="img-fluid rounded">
                                        </div>

                                        <!-- Product Details -->
                                        <div class="col-md-8">
                                            <h3 class="fw-bold mb-3">{{ $item->produk->nama_produk }}</h3>
                                            <div class="mb-3">
                                                <span class="badge bg-secondary me-2">Size: {{ $item->ukuran }}</span>
                                                @if (!empty($item->additional))
                                                    @foreach ($item->additional as $additionalItem)
                                                        <span
                                                            class="badge bg-info me-2">{{ $additionalItem['nama'] }}</span>
                                                    @endforeach
                                                @endif
                                            </div>
                                            <div class="alert alert-danger mb-3">
                                                <i class="fas fa-exclamation-circle me-2"></i>
                                                Status: <strong>{{ $item->status }}</strong>
                                            </div>
                                            <div class="alert alert-warning">
                                                <i class="fas fa-clock me-2"></i>
                                                MOHON SEGERA LAKUKAN PEMBAYARAN DIBAWAH 10 MENIT!
                                            </div>
                                        </div>

                                        <!-- Price and Action -->
                                        <div class="col-md-2 text-end">
                                            <h2 class="fw-bold mb-3">Rp{{ number_format($item->grand_total, 0, '', '.') }}
                                            </h2>
                                            <button id="pay-button-{{ $item->snapToken }}" class="btn btn-danger w-100">
                                                <i class="fas fa-credit-card me-2"></i>Checkout
                                            </button>
                                        </div>
                                    </div>
                                    @if (!$loop->last)
                                        <hr class="my-4">
                                    @endif
                                @endforeach
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                    <p class="lead text-muted">Kamu sedang tidak ada proses checkout!</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if ($checkout->isNotEmpty())
        @foreach ($checkout as $item)
            <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
            <script type="text/javascript">
                document.getElementById('pay-button-{{ $item->snapToken }}').onclick = function() {
                    $.ajax({
                        url: '{{ route('getTransaction') }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            transactionId: '{{ $item->snapToken }}',
                            nomor_order: '{{ $item->nomor_order }}',
                        },
                        success: function(response) {
                            if (response.success) {
                                snap.pay('{{ $item->snapToken }}', {
                                    onSuccess: function(result) {
                                        $.ajax({
                                            url: '{{ route('updateCheckout') }}',
                                            type: 'POST',
                                            data: {
                                                _token: '{{ csrf_token() }}',
                                                nomor_order: '{{ $item->nomor_order }}'
                                            },
                                            success: function(response) {
                                                if (response.success) {
                                                    window.location.href = response
                                                        .redirect_url;
                                                } else {
                                                    alert(
                                                        'Pembayaran berhasil, tetapi gagal memperbarui status pesanan.'
                                                        );
                                                }
                                            },
                                            error: function() {
                                                alert(
                                                    'Pembayaran berhasil, tetapi terjadi kesalahan saat memperbarui status pesanan.'
                                                    );
                                            }
                                        });
                                    },
                                    onPending: function(result) {
                                        document.getElementById('result-json').innerHTML += JSON
                                            .stringify(result, null, 2);
                                    },
                                    onError: function(result) {
                                        alert("error:" + result.status_code + result.error_messages);
                                        document.getElementById('result-json').innerHTML += JSON
                                            .stringify(result, null, 2);
                                    }
                                });
                            } else {
                                if (response.message == 'expired') {
                                    alert('Pembayaran Expired ! Mohon Order Kembali');
                                    window.location.href = response.redirect_url;
                                } else if (response.message == 'success') {
                                    window.location.href = response.redirect_url;
                                } else {
                                    alert('error lain');
                                }
                            }
                        },
                        error: function() {
                            console.log(response);
                            alert('Terjadi Kesahalah. Mohon Refresh halaman anda');
                        }
                    });
                };
            </script>
        @endforeach
    @endif
@endsection
