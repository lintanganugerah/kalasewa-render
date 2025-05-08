@if ($topproduk == 'tidak ada produk')
    <p> Anda belum memiliki produk. </p>
@elseif ($topproduk->isNotEmpty())
    <table class="table w-100 table-borderless table-responsive">
        <thead>
            <tr>
                <th>Foto</th>
                <th>Produk</th>
                <th width="4%">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($topproduk as $idproduk => $produk)
                <tr>
                    <td class="align-middle">
                        <div class="product-image-container me-3">
                            <img src="{{ asset($produk->first()->id_produk_order->FotoProduk->path) }}" alt="Produk"
                                class="product-image">
                        </div>
                    </td>
                    <td class="align-middle">
                        <div>
                            <h5 class="cut-text">{{ $produk->first()->id_produk_order->nama_produk }}</h5>
                            <small class="fw-light text-secondary">{{ $produk->first()->id_produk_order->brand }},
                                {{ $produk->first()->id_produk_order->gender }},
                                {{ $produk->first()->id_produk_order->series->series }},
                                {{ $produk->first()->id_produk_order->ukuran_produk }}</small>
                        </div>
                    </td>
                    <td class="align-middle">
                        <p class="m-0 small fw-bold card-link" href="#">
                            {{ $produk->count() }}x disewa</p>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p> Belum ada top produk</p>
@endif
