@extends('layout.selllayout')
@section('title', 'Dashboard')

@section('content')
    <div class="row">
        <div class="col">
            <div class="d-flex justify-content-between mb-5 mt-3">
                <div>
                    <h1 class="fw-bold text-secondary">Buat Ticketing Baru</h1>
                    <h5 class="fw-semibold text-secondary">Laporan permasalahan anda disini</h5>
                </div>
            </div>


            <div class="card">
                <div class="card-body">
                    <h4 class="mb-5">Informasi Permasalahan</h4>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            {{ $errors->first() }}
                        </div>
                    @endif
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
                    <form action="{{ route('seller.tiket.storeTicketingAction') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class='form-group'>
                            <label for='kategori_tiket_id'>Kategori <span class='text-danger'>*</span></label>
                            <select name='kategori_tiket_id' id='kategori_tiket_id'
                                class='form-control @error('kategori_tiket_id') is-invalid @enderror' required>
                                <option value='' selected disabled>Pilih Kategori</option>
                                @foreach ($data_kategori as $kategori)
                                    <option @selected($kategori->id == old('kategori_tiket_id')) value='{{ $kategori->id }}'>{{ $kategori->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori_tiket_id')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='deskripsi' class='mb-2'>Deskripsi Permasalahan <span
                                    class='text-danger'>*</span></label>
                            <textarea name='deskripsi' id='deskripsi' cols='30' rows='3'
                                class='form-control @error('deskripsi') is-invalid @enderror' required>{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='bukti' class='mb-2'>Bukti <span class='text-danger'>*</span></label>
                            <input type='file' name='bukti[]' id='bukti'
                                class='form-control @error('bukti') is-invalid @enderror' value='{{ old('bukti') }}'
                                accept=".jpg,.png,.jpeg,.webp,.mp4" required>
                            @error('bukti')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div id="additional-file-upload"></div>
                        <button type="button" class="btn btn-outline kalasewa-color my-3" id="addFileButton">Tambah File
                            Bukti</button>
                        <div class="form-group mb-3">
                            <button class="btn btn-danger btn-block">Buat Ticketing</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
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
            <div class="d-flex align-items-start">
                <div class="me-3">
                    <label for="bukti" class="mb-2">Bukti Tambahan</label>
                    <span class="btn" onclick="remove('bukti-${Date.now()}')"><i class="fas fa-trash"></i></span>
                </div>
            </div>
            <div class="flex-grow-1">
                <input type="file" name="bukti[]" class="form-control" id="fotoInput-${Date.now()}" accept=".jpg,.png,.jpeg,.webp">
                <div class="form-text error-message text-danger" id="FileError-${Date.now()}"></div>
            </div>`;
            // Menambahkan elemen baru ke dalam elemen dengan ID "photoInputs"
            buktiInput.appendChild(newBuktiInput);
        });

        function remove(id) {
            document.getElementById(id).remove();
        }
        // $(function() {
        //     $('#addFileButton').on('click', function(e) {
        //         e.preventDefault();
        //         var newFileUpload = $('<div class="form-group mb-3"></div>');
        //         var newLabel = $(
        //             '<label for="bukti" class="mb-2">Bukti Tambahan</label>'
        //         );
        //         var newFileInput = $(
        //             '<input type="file" name="bukti[]" class="form-control is-invalid">'
        //         );

        //         // Tambahkan label dan input file ke dalam div baru
        //         newFileUpload.append(newLabel);
        //         newFileUpload.append(newFileInput);

        //         // Tambahkan div baru ke dalam div dengan id 'additional-file-upload'
        //         $('#additional-file-upload').append(newFileUpload);
        //     });
        // })
    </script>
@endsection
