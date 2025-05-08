@extends('layout.selllayout')
@section('content')
    <div class="row">
        <div class="col">
            <a href="{{ route('seller.statuspenyewaan.telahkembali') }}" class="btn btn-outline-danger"><i
                    class="fa-solid fa-arrow-left fa-regular me-2"></i>Kembali</a>
            <div class="d-flex justify-content-between mb-4 mt-3">
                <div>
                    <h1 class="fw-bold text-secondary">Ajukan Denda Sewa</h1>
                    <h4 class="text-secondary">Anda akan mengajukan denda untuk penyewaan dengan nomor order
                        {{ $order->nomor_order }}</h4>
                </div>
            </div>
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif
            <hr>
            <div class="card mb-3">
                <div class="card-body btn text-start" type="button" id="toggleOrder">
                    <div class="m-2">
                        <h4 class="form-h4">Detail Order<i class="fa-solid fa-chevron-down ms-3" id="icon-chevron"></i></h4>
                    </div>
                </div>
                <div class="collapsing" id="detailOrder">
                    <div class="card-body">
                        <div class="m-2">
                            <div class="accordion-body">
                                <div class="text-dark rounded-3">
                                    <table id="tabel"
                                        class="no-more-tables table w-100 tabel-responsive align-items-center"
                                        style="word-wrap: break-word;">
                                        @if ($order)
                                            <thead>
                                                <tr>
                                                    <th>Nomor Order</th>
                                                    <th>Produk</th>
                                                    <th>Penyewa</th>
                                                    <th>Ukuran</th>
                                                    <th>Periode Sewa Tanggal</th>
                                                    <th>Harga Penyewaan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td data-title="No. Order" class="align-middle">
                                                        {{ $order->nomor_order }}</td>
                                                    <td data-title="Produk" class="align-middle">
                                                        <h5 class="">{{ $order->id_produk_order->nama_produk }}</h5>
                                                        <small class="fw-light" href=""
                                                            style="font-size:14px">{{ $order->id_produk_order->brand }},
                                                            Rp.{{ number_format($order->id_produk_order->harga) }}/3hari</small>
                                                    </td>
                                                    <td data-title="Nama Penyewa" class="align-middle">
                                                        <h5>{{ $order->id_penyewa_order->nama }}</h5>
                                                        <a class="fw-light"
                                                            href="{{ route('seller.view.penilaian.reviewPenyewa', $order->id_penyewa_order->id) }}"
                                                            style="font-size:14px">Lihat Review
                                                            Penyewa</a>
                                                    </td>
                                                    <td data-title="Ukuran" class="align-middle">
                                                        {{ $order->id_produk_order->ukuran_produk }}</td>
                                                    <td data-title="Periode Sewa" class="align-middle">
                                                        {{ $order->tanggalFormatted($order->tanggal_mulai) }} <span
                                                            class="fw-bolder"> s.d. </span>
                                                        {{ $order->tanggalFormatted($order->tanggal_selesai) }}
                                                    </td>
                                                    <td data-title="Total + Denda" class="align-middle">
                                                        {{ number_format($order->total_harga, 0, '', '.') }}</td>
                                                </tr>
                                            </tbody>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('seller.pengajuanDendaAction', $order->nomor_order) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="m-2">
                            <label for="exampleFormControlInput1" class="form-label">Nama
                                Peraturan<span class="text-danger mb-3">*</span></label>
                            <select class="form-select" aria-label="Default select example" id="optionPeraturan"
                                name="peraturan" required>
                                <option value="" selected disabled>Pilih Peraturan yang
                                    dilanggar</option>
                                @foreach ($order->id_produk_order->toko->peraturanSewaToko as $peraturan)
                                    @if ($peraturan->nama == 'Terlambat Mengembalikan Kostum')
                                    @else
                                        <option value="{{ $peraturan->id }}"
                                            data-denda-pasti="{{ $peraturan->denda_pasti }}">
                                            {{ $peraturan->nama }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <small>Jika peraturan tidak ada. <a
                                    href="{{ route('seller.profil.viewTambahPeraturanSewa') }}"> klik disini </a> untuk
                                menambahkan</small>
                        </div>
                        <div class="m-2">
                            <label for="hargaInput" class="form-label">Nominal Denda<span
                                    class="text-danger mb-3">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text" id="span_nominal">Rp.</span>
                                <input type="number" id="hargaInput" class="form-control" name="nominal_denda"
                                    placeholder="Masukkan tanpa titik" aria-label="Denda" pattern="[0-9]*" required>
                            </div>
                            <small>Jika peraturan memiliki nominal denda pasti. Maka kolom ini akan otomatis
                                terisi</small>
                        </div>
                        <div class="m-2">
                            <label for="textArea" class="form-label">Penjelasan<span
                                    class="text-danger mb-3">*</span></label>
                            <textarea class="form-control" id="textArea" rows="3" name="penjelasan" required></textarea>
                        </div>
                        <div class="m-2">
                            <label for="formFileSm" class="form-label">Bukti<span class="text-danger mb-3">*</span></label>
                            <input class="form-control form-control-sm" id="formFileSm" type="file"
                                accept=".jpg,.png,.jpeg,.webp" id="fotoInput-0" name="bukti[]" required>
                        </div>
                        <div id="additional-file-upload"></div>
                        <div class="m-2">
                            <button type="button" class="btn btn-outline kalasewa-color my-3" id="addFileButton">Tambah
                                File
                                Bukti</button>
                        </div>
                        <p class="m-2 mt-3 mb-4">Setelah submit pengajuan denda,
                            anda dapat melihat progress pengajuan tersebut di kolom "Denda Lainnya" pada tabel status
                            penyewaan yang telah kembali.
                        </p>
                        <button type="submit" class="btn w-100 btn-kalasewa">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('toggleOrder').addEventListener('click', toggleCollapse)
        document.getElementById('optionPeraturan').addEventListener('change', changeInput)

        function toggleCollapse() {
            const bodyCardDetail = document.getElementById('detailOrder');
            const icon = document.getElementById('icon-chevron');

            if (bodyCardDetail.classList.contains('collapse') && bodyCardDetail.classList.contains('show')) {
                bodyCardDetail.classList.remove('show');
                bodyCardDetail.classList.add('collapsing');
                icon.classList.add('fa-chevron-down');
                icon.classList.remove('fa-chevron-up');
            } else if (bodyCardDetail.classList.contains('collapsing')) {
                bodyCardDetail.classList.remove('collapsing');
                bodyCardDetail.classList.add('collapse');
                bodyCardDetail.classList.add('show');
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            }
        }

        function changeInput() {
            const inputElement = document.getElementById('hargaInput');
            const selectedOption = this.options[this.selectedIndex];
            const dendaPasti = selectedOption.getAttribute('data-denda-pasti');

            console.log(inputElement);
            console.log(selectedOption);
            console.log(dendaPasti);

            if (dendaPasti) {
                inputElement.value = dendaPasti;
                inputElement.readOnly = true; // Set input to read-only
            } else {
                inputElement.value = ''; // Clear the value
                inputElement.readOnly = false; // Make input editable
            }
        }

        document.getElementById("addFileButton").addEventListener("click", function() {
            // Mendapatkan elemen id di view, untuk naroh input baru nya nanti
            const buktiInput = document.getElementById("additional-file-upload");
            // Membuat elemen div baru untuk input foto baru
            const newBuktiInput = document.createElement("div");
            // Menambahkan class bootstrap dan photo-input
            newBuktiInput.classList.add("bukti-input", "mb-2");
            // Memberikan ID unik ke elemen baru
            newBuktiInput.id = `bukti-${Date.now()}`;
            // Mengatur isi HTML dari div baru
            newBuktiInput.innerHTML = `
            <div class="m-2">
                <div class="d-flex align-items-start">
                    <div class="me-3">
                        <label for="bukti" class="mb-2">Bukti Tambahan</label>
                        <span class="btn" onclick="remove('bukti-${Date.now()}')"><i class="fas fa-trash"></i></span>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <input type="file" name="bukti[]" class="form-control form-control-sm" id="fotoInput-${Date.now()}" accept=".jpg,.png,.jpeg,.webp">
                    <div class="form-text error-message text-danger" id="FileError-${Date.now()}"></div>
                </div>
            </div>`;
            // Menambahkan elemen baru ke dalam elemen dengan ID "photoInputs"
            buktiInput.appendChild(newBuktiInput);
        });

        function remove(id) {
            document.getElementById(id).remove();
        }
    </script>
@endsection
