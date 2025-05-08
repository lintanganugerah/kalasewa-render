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
                <h1 class="fw-bold text-secondary">Edit Alamat</h1>
                <h4 class="fw-semibold text-secondary">Anda akan melakukan edit informasi alamat</h4>
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
                                <form action="{{ route('seller.profil.EditAlamatTambahanAction', $at->id) }}" method="POST"
                                    id="formToko" enctype="multipart/form-data">
                                    @csrf
                                    <div class="fw-bolder fs-5 mb-3">Informasi Alamat</div>
                                    <div class="mb-3">
                                        <label for="alamatName">Nama Alamat</label>
                                        <input type="text" class="form-control error-check" id="alamatName"
                                            name="alamatNameTambahan"
                                            @error('alamatNameTambahan') style="border-color: red;" @enderror
                                            value="{{ old('alamatNameTambahan', $at->nama) }}" required>
                                        @error('alamatNameTambahan')
                                            <div class="text-danger form-text error-message" data-milik="alamatNameTambahan">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        <small id="helpNama" class="form-text text-muted mb-3">Masukkan nama alamat yang
                                            Anda ingat. Misal: Rumah/Kantor/Rumah Budi</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="alamat">Alamat</label>
                                        <textarea class="form-control error-check" id="alamatIsi" rows="5" name="alamatTambahan"
                                            @error('alamatTambahan') style="border-color: red;" @enderror required>{{ old('alamatTambahan', $at->alamat) }}</textarea>
                                        @error('alamatTambahan')
                                            <div class="text-danger form-text error-message" data-milik="alamatTambahan">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="provinsiTambahan" class="form-label">Provinsi<span
                                                class="text-danger">*</span></label>
                                        <select class="form-select error-check" id="floatingSelect" name="provinsiTambahan"
                                            aria-label="Floating select example"
                                            @error('provinsiTambahan') style="border-color: red;" @enderror required>
                                            <option value="Jawa Barat"
                                                {{ old('provinsiTambahan', $at->provinsi) == 'Jawa Barat' ? 'selected' : '' }}>
                                                Jawa Barat</option>
                                        </select>
                                        @error('provinsiTambahan')
                                            <div class="text-danger form-text error-message" data-milik="provinsiTambahan">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="kotaTambahan" class="form-label">Kota/Kabupaten<span
                                                class="text-danger">*</span></label>
                                        <select class="form-select error-check" id="kotaSelect" name="kotaTambahan"
                                            @error('kotaTambahan') style="border-color: red;" @enderror required>
                                            <option value="Kota Bandung"
                                                {{ old('kotaTambahan', $at->kota) == 'Kota Bandung' ? 'selected' : '' }}>
                                                Kota Bandung</option>
                                            <option value="Kabupaten Bandung"
                                                {{ old('kotaTambahan', $at->kota) == 'Kabupaten Bandung' ? 'selected' : '' }}>
                                                Kabupaten Bandung</option>
                                        </select>
                                        @error('kotaTambahan')
                                            <div class="text-danger form-text error-message" data-milik="kotaTambahan">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    
                                    

                                    <div class="mb-3">
                                        <label class="form-label" for="kodePos">Kode Pos<span
                                                class="text-danger">*</span></label>
                                            
                                        <select class="form-select select2" id="kodePos" name="kodePosTambahan"
                                            pattern="[0-9]*" id="kodePos" minlength="5"
                                            @error('kodePosTambahan') style="border-color: red;" @enderror maxlength="5" data-kodePos="{{ old('kodePosTambahan', $at->kode_pos) }}" required>
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
    <script src="{{ asset('seller/inputangka.js') }}"></script>
    <script src="{{ asset('seller/kode_pos.js') }}"></script>
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
