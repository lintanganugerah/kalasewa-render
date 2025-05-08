<ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link text-secondary {{ request()->is('status-sewa/belumdiproses') ? 'active fw-bold' : '' }}"
            id="Produkanda-tab" data-bs-toggle="tab" onclick="window.location.href='/status-sewa/belumdiproses'"
            type="button" role="tab" aria-controls="Produkanda" aria-selected="true">Belum
            Diproses
            ({{ auth()->user()->toko->countPenyewaanAllBerstatusTabs('Menunggu di Proses') }})</span></button>
    </li>
    <li class="nav-item" role="presentation">
        <button
            class="nav-link text-secondary {{ request()->is('status-sewa/dalampengiriman') ? 'active fw-bold' : '' }}"
            id="tambahProduk-tab" onclick="window.location.href='/status-sewa/dalampengiriman'" type="button"
            role="tab" aria-controls="tambahProduk" aria-selected="false">Dalam
            Pengiriman
            ({{ auth()->user()->toko->countPenyewaanAllBerstatusTabs('Dalam Pengiriman') }})</button>
    </li>
    <li class="nav-item" role="presentation">
        <button
            class="nav-link text-secondary {{ request()->is('status-sewa/sedangberlangsung') ? 'active fw-bold' : '' }}"
            id="tambahProduk-tab" onclick="window.location.href='/status-sewa/sedangberlangsung'" type="button"
            role="tab" aria-controls="tambahProduk" aria-selected="false">Sedang
            Berlangsung
            ({{ auth()->user()->toko->countPenyewaanAllBerstatusTabs('Sedang Berlangsung') }})</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link text-secondary {{ request()->is('status-sewa/telahkembali') ? 'active fw-bold' : '' }}"
            id="tambahProduk-tab" onclick="window.location.href='/status-sewa/telahkembali'" type="button"
            role="tab" aria-controls="tambahProduk" aria-selected="false">Penyewaan Telah kembali
            ({{ auth()->user()->toko->countPenyewaanAllBerstatusTabs('Telah Kembali') }})</button>
    </li>
    <li class="nav-item" role="presentation">
        <button
            class="nav-link text-secondary {{ request()->is('status-sewa/penyewaandiretur') ? 'active fw-bold' : '' }}"
            id="tambahProduk-tab" onclick="window.location.href='/status-sewa/penyewaandiretur'" type="button"
            role="tab" aria-controls="tambahProduk" aria-selected="false">Penyewaan
            diretur
            ({{ auth()->user()->toko->countPenyewaanAllBerstatusTabs('ReturAll') }})</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link text-secondary {{ request()->is('status-sewa/riwayat') ? 'active fw-bold' : '' }}"
            id="tambahProduk-tab" onclick="window.location.href='/status-sewa/riwayat'" type="button" role="tab"
            aria-controls="tambahProduk" aria-selected="false">Riwayat
            Penyewaan
            ({{ auth()->user()->toko->riwayatPenyewaan() }})</button>
    </li>
</ul>
