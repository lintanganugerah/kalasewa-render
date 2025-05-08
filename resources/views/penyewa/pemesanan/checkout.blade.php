@extends('layout.template')

@section('content')
    <section>
        <div class="container-fluid mt-5">
            <div class="container">
                <div class="col text-center">
                    <h1><strong>Checkout Pesanan Kamu!</strong></h1>
                </div>
            </div>
        </div>

        <div class="container mt-2 mb-3">
            @csrf
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
        </div>

        <div class="container-fluid mt-5">
            <div class="container">
                <div class="card">
                    <div class="card-body w-100">
                        @if ($checkout->isNotEmpty())
                            @foreach ($checkout as $item)
                                <div class="row">
                                    <div class="col-2">
                                        <img src="{{ asset($item->produk->fotoProduk->path) }}" alt="Catalog"
                                            class="img-thumbnail">
                                    </div>
                                    <div class="col-8">
                                        <h3><strong>{{ $item->produk->nama_produk }}</strong></h3>
                                        <p>Size : {{ $item->ukuran }}</p>
                                        <p>Additional:
                                            @if (!empty($item->additional))
                                                @foreach ($item->additional as $additionalItem)
                                                    {{ $additionalItem['nama'] }}
                                                @endforeach
                                            @endif
                                        </p>
                                        <p>Status: <span class="text-danger">{{ $item->status }}</span></p>
                                        <p class="text-danger fw-bold">MOHON SEGERA LAKUKAN PEMBAYARAN DIBAWAH 10 MENIT!
                                        </p>
                                    </div>
                                    <div class="col-2 row text-end align-self-end">
                                        <h2><strong>Rp{{ number_format($item->grand_total, 0, '', '.') }}</strong></h2>
                                        {{-- <form action="{{ route('tesCekCheckout') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="transactionId"
                                                class="form-control form-control-lg w-100" value="{{ $item->snapToken }}"
                                                required />
                                            <input type="hidden" name="nomor_order"
                                                class="form-control form-control-lg w-100" value="{{ $item->nomor_order }}"
                                                required />
                                            <button type="submit" class="btn btn-danger">Checkout</button>
                                        </form> --}}
                                        <button id="pay-button-{{ $item->snapToken }}"
                                            class="btn btn-danger">Checkout</button>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-center">Kamu sedang tidak ada proses checkout!</p>
                        @endif
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
                                        /* You may add your own js ;here, this is just example */
                                        document.getElementById('result-json').innerHTML += JSON
                                            .stringify(result, null, 2);
                                    },
                                    onError: function(result) {
                                        alert("error:" + result.status_code + result.error_messages);
                                        /* You may add your own js here, this is just example */
                                        document.getElementById('result-json').innerHTML += JSON
                                            .stringify(result, null, 2);
                                    }
                                });
                            } else {
                                if (response.message == 'expired') {
                                    alert(
                                        'Pembayaran Expired ! Mohon Order Kembali'
                                    );
                                    window.location.href = response
                                        .redirect_url;
                                } else if (response.message == 'success') {
                                    window.location.href = response
                                        .redirect_url;
                                } else {
                                    alert('error lain');
                                }
                            }
                        },
                        error: function() {
                            console.log(response);
                            alert(
                                'Terjadi Kesahalah. Mohon Refresh halaman anda'
                            );
                        }
                    });
                };
            </script>
            {{-- <script type="text/javascript">
                document.getElementById('pay-button-{{ $item->snapToken }}').onclick = function() {
                    SnapToken acquired from previous step
                    $.ajax({
                        url: '{{ route('cekStatusProduk') }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            nomor_order: '{{ $item->nomor_order }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                snap.pay('{{ $item->snapToken }}', {
                                    Optional
                                    onSuccess: function(result) {
                                        AJAX call to update status
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
                                    // Optional
                                    onPending: function(result) {
                                        /* You may add your own js ;here, this is just example */
                                        document.getElementById('result-json').innerHTML += JSON
                                            .stringify(result, null, 2);
                                    },
                                    Optional
                                    onError: function(result) {
                                        alert("error:" + result.status_code + result.error_messages);
                                        /* You may add your own js here, this is just example */
                                        document.getElementById('result-json').innerHTML += JSON
                                            .stringify(result, null, 2);
                                    }
                                });
                            } else {
                                alert(
                                    'Maaf! Produk Telah Dirental oleh orang lain. Produk dihapus dari checkout'
                                );
                                window.location.href = response.redirect_url;
                            }
                        },
                        error: function() {
                            console.log(response);
                            alert(
                                'Terjadi Kesahalah. Mohon Refresh halaman anda'
                            );
                        }
                    });
                };
            </script> --}}
        @endforeach
    @endif
@endsection
