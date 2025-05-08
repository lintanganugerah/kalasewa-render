@extends('layout.selllayout')
@section('content')
<div class="row">
    <div class="col">
        <div class="text-left mb-5 mt-3">
            <h1 class="fw-bold text-secondary">Profil Toko</h1>
            <h4 class="fw-semibold text-secondary">Manajemen Informasi Toko Anda</h4>
        </div>

        <div class="row gx-5">
            <div class="card">
                <div class="card-header">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active text-secondary fw-bold" id="profil-tab"
                                onclick="window.location.href='/profil/toko'" type="button" role="tab"
                                aria-controls="profil" aria-selected="true">Profil</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link text-secondary" id="profil-tab"
                                onclick="window.location.href='/profil/toko/AlamatTambahan'" type="button"
                                role="tab" aria-selected="false">Alamat Lainnya</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link text-secondary" id="contact-tab"
                                onclick="window.location.href='/profil/toko/peraturansewa'" type="button"
                                role="tab" aria-selected="false">Peraturan
                                Sewa Toko
                                Anda</button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="profil" role="tabpanel"
                            aria-labelledby="profil-tab">
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
                            <form action="{{ route('seller.profilTokoAction') }}" method="POST" id="formToko"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="userPhoto" class="form-label">Foto Profil</label>
                                    <div class="d-flex align-items-start">
                                        <div id="userPhotoContainer" class="me-3">
                                            <img id="userPhotoPreview" src="{{ asset(session('profilpath')) }}"
                                                style="width:150px; height:150px; object-fit: cover;" alt="User Photo"
                                                class="img-thumbnail">
                                        </div>
                                        <div class="flex-grow-1">
                                            <small class="form-text text-muted">
                                                <ul>
                                                    <li>Disarankan Rasio foto 1:1 atau object berada di tengah</li>
                                                    <li>Ukuran max 5MB</li>
                                                    <li>JPG, JPEG, PNG, WEBP</li>
                                                </ul>
                                            </small>
                                            <input type="file" name="foto" class="form-control mb-2"
                                                id="userPhoto" accept=".jpg,.png,.jpeg,.webp">
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Nama Toko<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="exampleFormControlInput1"
                                        value="{{ session('namatoko') }}" name="namaToko" required>
                                </div>
                                @if ($toko->bio_toko)
                                    <div class="mb-3">
                                        <label for="exampleFormControlTextarea1" class="form-label">Bio Toko</label>
                                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="10" name="bio_toko">{{ $toko->bio_toko }}</textarea>
                                    </div>
                                @else
                                    <div class="mb-3">
                                        <label for="exampleFormControlTextarea1" class="form-label">Bio Toko</label>
                                        <textarea class="form-control" rows="5" name="bio_toko"></textarea>
                                        <div id="HELP" class="form-text fw-light">Berikan informasi tentang toko
                                            anda (Opsional)
                                        </div>
                                    </div>
                                @endif
                                <div class="mb-3">
                                    <label class="form-label" for="form2Example17">Link Sosial Media<span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="form2Example17" class="form-control"
                                        value="{{ $user->link_sosial_media }}" name="link_sosial_media" required />
                                </div>
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Nomor Telpon<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nomor_telpon"
                                        value="{{ $user->no_telp }}" name="nomor_telpon"minlength="10" maxlength="14"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="exampleFormControlTextarea1" class="form-label">Alamat Utama Toko<span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="5" name="AlamatToko" required>{{ $user->alamat }}</textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="exampleFormControlTextarea1" class="form-label">Provinsi<span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="provinsiSelect" name="provinsi"
                                        aria-label="Floating select example" required>
                                        <option value="Jawa Barat"
                                            {{ $user->kota == 'Jawa Barat' ? 'selected' : '' }}>
                                            Jawa Barat</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="exampleFormControlTextarea1" class="form-label">Kota/Kabupaten<span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="kotaSelect" name="kota"
                                        aria-label="Floating label select example" required>
                                        <option value="Kota Bandung"
                                            {{ $user->kota == 'Kota Bandung' ? 'selected' : '' }}>Kota Bandung</option>
                                        <option value="Kabupaten Bandung"
                                            {{ $user->kota == 'Kabupaten Bandung' ? 'selected' : '' }}>Kabupaten
                                            Bandung
                                        </option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <div data-mdb-input-init class="form-outline">
                                        <label class="form-label" for="kodePos">Kode Pos<span
                                                class="text-danger">*</span></label>
                                        <select class="form-select select2" id="kodePos" name="kodePos" data-kodePos="{{ $user->kode_pos ?? 0}}" required>
                                        </select>
                                        <div class="form-text error-message text-danger" id="kodePosError"></div>
                                    </div>
                                </div>

                                <hr class="border border-secondary border-3 my-5">
                                <!-- Informasi Alamat Lainnya -->
                                <h5>Informasi Alamat Lainnya</h5>
                                <div id="helpberat" class="mb-3" style="opacity: 75%;">Jika produk/kostum anda
                                    tersebar di berbagai lokasi, anda bisa menambahkan alamat baru. Nantinya
                                    anda bisa memilih lokasi produk/kostum anda berada di alamat yang mana. <a
                                        id="addAlamat" style="color: #D44E4E; text-decoration: underline!important;"
                                        href="{{ route('seller.profil.viewAlamatTambahanToko') }}">Klik disini untuk
                                        tambah alamat</a></div>
                                <hr class="border border-secondary border-3 my-5">
                                <div class="d-grid mb-5">
                                    <button class="btn btn-kalasewa btn-lg btn-block" type="submit">Simpan
                                        perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('seller/validasiProfilToko.js') }}"></script>
<script src="{{ asset('seller/kode_pos.js') }}"></script>
<script src="{{ asset('seller/inputangka.js') }}"></script>
<script>
    $('.select2').select2({
        theme: "bootstrap-5",
        width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
        height: $(this).data('height') ? $(this).data('height') : $(this).hasClass('h-100') ? '100%' : 'style',
    });
</script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection