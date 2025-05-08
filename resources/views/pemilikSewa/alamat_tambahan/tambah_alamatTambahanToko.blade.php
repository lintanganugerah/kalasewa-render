@extends('layout.selllayout')
@section('content')
    <div class="row">
        <div class="col">
            <div class="text-left mb-5 mt-3 ml-4">
                <!-- Tombol Back -->
                <div class="text-left mt-3 mb-4">
                    <a href="{{ route('seller.profil.viewAlamatTambahanToko') }}" class="btn btn-outline kalasewa-color"><i
                            class="fa-solid fa-arrow-left fa-regular me-2"></i>Kembali</a>
                </div>
                <h1 class="fw-bold text-secondary">Tambah Alamat</h1>
                <h4 class="fw-semibold text-secondary">Masukan informasi detail alamat tersebut disini</h4>
            </div>

            <div class="row gx-5">

                <div class="card">
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
                                @if (session('error'))
                                    <div class="alert alert-danger">
                                        {{ session('error') }}
                                    </div>
                                @endif
                                <form action="{{ route('seller.profil.TambahAlamatTambahanAction') }}" method="POST"
                                    id="formToko" enctype="multipart/form-data">
                                    @csrf
                                    <div class="fw-bolder fs-5 mb-3">Informasi Alamat</div>
                                    <div class="mb-3">
                                        <label for="alamatName">Nama Alamat</label>
                                        <input type="text" class="form-control" id="alamatName" name="alamatNameTambahan"
                                            required>
                                        <small id="helpNama" class="mb-3" style="opacity: 75%;">Masukan nama alamat
                                            yang anda ingat.
                                            Misal: Rumah/Kantor/Rumah Budi
                                        </small>
                                    </div>
                                    <div class="mb-3">
                                        <label for="alamat">Alamat</label>
                                        <textarea type="text" class="form-control alamat-" id="alamatIsi" rows="10" name="alamatTambahan" required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="exampleFormControlTextarea1" class="form-label">Provinsi<span
                                                class="text-danger">*</span></label>
                                        <select class="form-select" id="floatingSelect" name="provinsiTambahan"
                                            aria-label="Floating select example" required>
                                            <option value="Jawa Barat">
                                                Jawa Barat</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="exampleFormControlTextarea1" class="form-label">Kota/Kabupaten<span
                                                class="text-danger">*</span></label>
                                        <select class="form-select" id="kotaSelect" name="kotaTambahan"
                                            aria-label="Floating label select example" required>
                                            <option value="Kota Bandung">
                                                Kota Bandung</option>
                                            <option value="Kabupaten Bandung">
                                                Kabupaten
                                                Bandung
                                            </option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="kodePos">Kode Pos<span
                                                class="text-danger">*</span></label>
                                            
                                        <select class="form-select select2" id="kodePos" name="kodePosTambahan"
                                            pattern="[0-9]*" id="kodePos" minlength="5"
                                            @error('kodePosTambahan') style="border-color: red;" @enderror maxlength="5" data-kodePos="{{ old('kodePosTambahan') }}" required>
                                        </select>
                                        <div class="form-text error-message text-danger" id="kodePosError"></div>
                                        @error('kodePosTambahan')
                                            <div class="text-danger form-text error-message" data-milik="kodePosTambahan">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="d-grid my-5">
                                        <button class="btn btn-kalasewa btn-lg btn-block" type="submit">Tambah
                                            Alamat</button>
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
