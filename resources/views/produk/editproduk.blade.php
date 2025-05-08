@extends('layout.selllayout')
@section('content')
    <div class="row">
        <div class="col">

            <div class="text-left mb-5 mt-3 ml-4">
                <!-- Tombol Back -->
                <div class="text-left mt-3 mb-3">
                    <a href="{{ route('seller.viewProdukAnda') }}" class="btn btn-outline kalasewa-color"><i
                            class="fa-solid fa-arrow-left fa-regular me-2"></i>Kembali</a>
                </div>
                <h1 class="fw-bold text-secondary">Produk</h1>
                <h4 class="fw-semibold text-secondary">Edit Produk Anda</h4>
            </div>

            <div class="row gx-5">

                <div class="card">
                    <div class="card-body">
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="Informasi" role="tabpanel"
                                aria-labelledby="Informasi-tab">
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
                                <h5 class="card-title">Informasi Produk</h5>

                                <label class="form-label">Foto Produk</label>

                                @foreach ($fotoProduk->where('id_produk', $produk->id) as $fp)
                                    <div class=" mb-2">
                                        <div class="d-flex align-items-start">
                                            <img class="" src="{{ asset($fp->path) }}"
                                                style="width: 150px; height: 150px; object-fit: cover;" alt="Foto Produk">
                                            <form action="{{ route('seller.hapusFoto', $fp->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-danger ml-2"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')"><i
                                                        class="fa-solid fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                                <form action="{{ route('seller.editProdukAction', $produk->id) }}" method="POST"
                                    id="formproduk" enctype="multipart/form-data" class="mt-5">
                                    @csrf
                                    <label class="form-label">Tambah Foto Produk (Opsional)</label>
                                    <div class="mb-3">
                                        <div id="photoInputs">
                                        </div>
                                        <div class="flex-grow-1">
                                            <small class="form-text text-muted">
                                                <ul>
                                                    <li>Disarankan Rasio foto 1:1 atau object berada di tengah</li>
                                                    <li>Ukuran max 5MB</li>
                                                    <li>JPG, JPEG, PNG, WEBP</li>
                                                </ul>
                                            </small>
                                        </div>
                                        <button type="button" id="addPhotoBtn" class="btn text-white"
                                            style="background-color: #D44E4E">Klik untuk Tambah Foto</button>
                                    </div>

                                    <div class="mb-3">
                                        <label for="exampleFormControlInput1" class="form-label">Nama Produk<span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="exampleFormControlInput1"
                                            name="namaProduk" value="{{ old('namaProduk', $produk->nama_produk) }}"
                                            required>
                                        <div id="namaProduk" class="form-text" style="opacity: 50%;">Disarankan untuk
                                            memasukkan nama series pada
                                            produk agar penyewa gampang menemukan dari seris apa produks cosplay anda</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="exampleFormControlTextarea1" class="form-label">Deskripsi Produk<span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="10" name="deskripsiProduk" required>{{ old('deskripsiProduk', $produk->deskripsi_produk) }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="exampleFormControlInput1" class="form-label">Brand<span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="exampleFormControlInput1"
                                            name="brand" value="{{ old('brand', $produk->brand) }}" required>
                                        <div id="namaProduk" class="form-text" style="opacity: 50%;">Jika tidak ada brand
                                            silahkan tuliskan Buatan Sendiri/No Brand</div>
                                    </div>
                                    <label for="selectSeries" class="form-label">Series<span
                                            class="text-danger">*</span></label>
                                    <div class="form-floating mb-3">
                                        <select class="form-select select2" id="selectSeries" name="series" required>
                                            <option></option>
                                            @foreach ($series as $sr)
                                                <option value="{{ $sr->series }}"
                                                    {{ old('series', $produk->series->series) == $sr->series ? 'selected' : '' }}>
                                                    {{ $sr->series }}</option>
                                            @endforeach
                                        </select>
                                        <label for="selectSeries">Series</label>
                                        <div id="namaProduk" class="form-text" style="opacity: 50%;">Jika series anda tidak
                                            ada. Silahkan ketik nama series lalu enter</div>
                                    </div>
                                    <label for="selectGender" class="form-label">Gender<span
                                            class="text-danger">*</span></label>
                                    <div class="form-floating mb-3">
                                        <select class="form-select select2" id="selectGender" name="gender" required>
                                            <option></option>
                                            <option value="Pria"
                                                {{ old('gender', $produk->gender) == 'Pria' ? 'selected' : '' }}>Pria
                                            </option>
                                            <option value="Wanita"
                                                {{ old('gender', $produk->gender) == 'Wanita' ? 'selected' : '' }}>
                                                Wanita</option>
                                            <option value="Semua Gender"
                                                {{ old('gender', $produk->gender) == 'Semua Gender' ? 'selected' : '' }}>
                                                Semua Gender</option>
                                        </select>
                                    </div>
                                    <label for="selectUkuran" class="form-label">Ukuran<span
                                            class="text-danger">*</span></label>
                                    <div class="form-floating mb-3">
                                        <select class="form-select select2" name="ukuran" required>
                                            <option></option>
                                            <option value="XS"
                                                {{ old('ukuran', $produk->ukuran_produk) == 'XS' ? 'selected' : '' }}>
                                                XS
                                            </option>
                                            <option value="S"
                                                {{ old('ukuran', $produk->ukuran_produk) == 'S' ? 'selected' : '' }}>S
                                            </option>
                                            <option value="M"
                                                {{ old('ukuran', $produk->ukuran_produk) == 'M' ? 'selected' : '' }}>M
                                            </option>
                                            <option value="L"
                                                {{ old('ukuran', $produk->ukuran_produk) == 'L' ? 'selected' : '' }}>L
                                            </option>
                                            <option value="XL"
                                                {{ old('ukuran', $produk->ukuran_produk) == 'XL' ? 'selected' : '' }}>
                                                XL
                                            </option>
                                            <option value="XXL"
                                                {{ old('ukuran', old('ukuran', $produk->ukuran_produk)) == 'XXL' ? 'selected' : '' }}>
                                                XXL
                                            </option>
                                            <option value="All_Size"
                                                {{ old('ukuran', $produk->ukuran_produk) == 'All_Size' ? 'selected' : '' }}>
                                                All Size
                                            </option>
                                        </select>
                                    </div>
                                    <label for="harga" class="form-label">Harga<span
                                            class="text-danger">*</span></label>
                                    <div class="input-group mb-3">
                                        <div class="form-floating">
                                            <input type="number" id="hargaInput" class="form-control" name="harga"
                                                placeholder="Harga" value="{{ old('harga', $produk->harga) }}"
                                                pattern="^[0-9]*$" required>
                                            <label for="harga">Harga / 3
                                                hari</label>
                                        </div>
                                        <span id="harga_span" class="input-group-text fw-100">/ 3 hari</span>
                                    </div>
                                    <div id="labelhelp1_${targetId}" class="form-text mb-3" style="opacity: 50%;">
                                        Masukan
                                        harga tanpa titik. Contoh : 150000</div>

                                    <hr class="border border-secondary border-3 my-5">
                                    <!-- Informasi Lainnya -->
                                    <h5>Informasi Tambahan Produk</h5>
                                    <div id="helpberat" class="mb-3" style="opacity: 75%;">Mohon masukkan infomasi
                                        tambahan produk dibawah ini. Klik icon i jika anda bingung mengenai field
                                        tersebut</div>
                                    <label for="grade" class="form-label">Grade Kostum<span
                                            class="text-danger">*</span></label><a data-bs-toggle="modal"
                                        data-bs-target="#infoModalGrade"><i
                                            class="fa-solid fa-regular fa-circle-info ms-2"></i></a>
                                    <div class="mb-3">
                                        <select class="form-select" name="grade" required>
                                            <option value="" disabled selected style="opacity: 10%;">Berapa grade
                                                kostum ini?
                                            </option>
                                            <option value="Grade 1"
                                                {{ old('grade', $produk->grade) == 'Grade 1' ? 'selected' : '' }}>
                                                Grade 1
                                            </option>
                                            <option value="Grade 2"
                                                {{ old('grade', $produk->grade) == 'Grade 2' ? 'selected' : '' }}>
                                                Grade 2</option>
                                            <option value="Grade 3"
                                                {{ old('Grade 3', $produk->grade) == 'Grade 3' ? 'selected' : '' }}>
                                                Grade 3
                                            </option>
                                            @error('grade')
                                                <div class="text-danger form-text">{{ $message }}</div>
                                            @enderror
                                        </select>
                                    </div>
                                    <label for="biaya_cuci" class="form-label">Apakah ada biaya cuci untuk kostum
                                        ini?<span class="text-danger">*</span></label><a data-bs-toggle="modal"
                                        data-bs-target="#infoModalCuci"><i
                                            class="fa-solid fa-regular fa-circle-info ms-2"></i></a>
                                    <div class="mb-3">
                                        <input type="radio" id="cuciTidak" name="cuci" value="tidak"
                                            onchange="opsiCuci()" {{ old('cuci') == 'tidak' ? 'checked' : '' }}
                                            {{ old('cuci') == null && $produk->biaya_cuci == null ? 'checked' : '' }}
                                            required>
                                        Tidak
                                        <input type="radio" id="cuciYa" name="cuci" value="ya"
                                            onchange="opsiCuci()" {{ old('cuci') == 'ya' ? 'checked' : '' }}
                                            {{ old('cuci') == null && $produk->biaya_cuci != null ? 'checked' : '' }}> Ya
                                        @error('cuci')
                                            <div class="text-danger form-text">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div id="optionsBiayaCuci" class="mb-3">
                                        {{-- Inputan biaya cuci di isi nanti disini --}}

                                        @if (old('biaya_cuci') || $produk->biaya_cuci)
                                            <div class="mb-2" id="optionsCuci">
                                                <label for="biaya_cuci" class="form-label">Biaya Cuci<span
                                                        class="text-danger mb-3">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text" id="span_nominal">Rp.</span>
                                                    <input type="number" id="biaya_cuci" class="form-control"
                                                        name="biaya_cuci" placeholder="20000" aria-label="cuci"
                                                        pattern="[0-9]*"
                                                        value="{{ old('biaya_cuci', $produk->biaya_cuci) }}" required>
                                                </div>
                                                <small id="helpnominal" class="mb-3" style="opacity: 75%;">Masukan
                                                    Angka Tanpa
                                                    Titik. Contoh : 20000</small>
                                                @error('biaya_cuci')
                                                    <div class="text-danger form-text">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        @endif
                                    </div>
                                    <label for="wig" class="form-label">Apakah katalog produk ini termasuk wig
                                        dalam penyewaan?<span class="text-danger">*</span></label><a
                                        data-bs-toggle="modal" data-bs-target="#infoModalWig"><i
                                            class="fa-solid fa-regular fa-circle-info ms-2"></i></a>
                                    <div class="mb-3">
                                        <input type="radio" id="wigTidak" name="wig_opsi" value="tidak"
                                            onchange="opsiWig()" {{ old('wig_opsi') == 'tidak' ? 'checked' : '' }}
                                            {{ old('wig_opsi') == null && $produk->brand_wig == null && $produk->keterangan_wig == null ? 'checked' : '' }}
                                            required> Tidak
                                        <input type="radio" id="wigYa" name="wig_opsi" value="ya"
                                            onchange="opsiWig()" {{ old('wig_opsi') == 'ya' ? 'checked' : '' }}
                                            {{ old('wig_opsi') == null && $produk->brand_wig != null ? 'checked' : '' }}>
                                        Ya
                                    </div>
                                    <div id="optionsWig" class="mb-3">
                                        {{-- Inputan brand, dan styling wig di isi nanti disini --}}

                                        @if (old('brand_wig') || old('ket_wig') || $produk->brand_wig)
                                            @if (old('wig_opsi') == 'tidak')
                                            @else
                                                <div class="mb-2" id="infoWig">
                                                    <div class="mb-3">
                                                        <label for="brand_wig" class="form-label">Brand wig<span
                                                                class="text-danger mb-3">*</span></label>
                                                        <input type="text" id="brand_wig" class="form-control"
                                                            name="brand_wig" aria-label="brand_wig"
                                                            value="{{ old('brand_wig', $produk->brand_wig) }}" required>
                                                        <small id="helpnominal" class="mb-3"
                                                            style="opacity: 75%;">Masukan
                                                            No Brand jika tidak ada brand</small>
                                                        @error('brand_wig')
                                                            <div class="text-danger form-text">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="ket_wig" class="form-label">Keterangan Styling
                                                            Wig<span class="text-danger mb-3">*</span></label>
                                                        <input type="text" id="ket_wig"
                                                            placeholder="Soft Styling/Hard Styling/No Styling"
                                                            class="form-control" name="ket_wig" aria-label="ket_wig"
                                                            value="{{ old('ket_wig', $produk->keterangan_wig) }}"
                                                            required>
                                                        @error('ket_wig')
                                                            <div class="text-danger form-text">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                    @if ($alamatTambahan)
                                        <hr class="border border-secondary border-3 my-5">
                                        <!-- Informasi Alamat -->
                                        <h5>Informasi Alamat</h5>
                                        <div id="helpberat" class="mb-3" style="opacity: 75%;">Anda memiliki alamat
                                            tambahan. Mohon pilih dimana produk/kostum ini berada</div>
                                        <label for="ket_wig" class="form-label">Dimana Alamat Produk ini?<span
                                                class="text-danger mb-3">*</span></label>
                                        <div class="mb-3">
                                            <input type="radio" id="default_alamat" name="alamat_opsi" value="default"
                                                {{ old('alamat_opsi') == 'default' ? 'checked' : '' }}
                                                {{ $produk->id_alamat == null ? 'checked' : '' }} required>
                                            Alamat Utama
                                            @foreach ($alamatTambahan as $al)
                                                <input type="radio" name="alamat_opsi" value="{{ $al->id }}"
                                                    {{ old('alamat_opsi', $produk->id_alamat) == $al->id ? 'checked' : '' }}
                                                    required>
                                                {{ $al->nama }}
                                            @endforeach
                                        </div>
                                    @endif

                                    <hr class="border border-secondary border-3 my-5">
                                    <!-- Informasi Additional -->
                                    <h5>Informasi Barang Additional</h5>
                                    <div id="helpberat" class="mb-3" style="opacity: 75%;">Jika Anda memiliki
                                        tambahan barang misalnya seperti aksesoris tambahan, masukan informasi tersebut
                                        disini beserta harga</div>
                                    <div id="additionalInputs">
                                        @if ($produk->additional && old('additional') == null)
                                            @foreach ($decodeAdd as $nama => $harga)
                                                <div class="additional-input mb-3"
                                                    id="additional-{{ $nama }}-{{ $loop->iteration }}">
                                                    <!-- $loop disediakan oleh laravel untuk cek ini iterasi ke berapa -->
                                                    <div class="fw-bolder fs-5">Additional <span class="btn"
                                                            onclick="removeAdditionalInput('additional-{{ $nama }}-{{ $loop->iteration }}')"><i
                                                                class="fas fa-trash"></i></span></div>

                                                    <div class="form-group">
                                                        <label for="additionalName-1">Nama Additional</label>
                                                        <input type="text" class="form-control" name="additional[]"
                                                            value="{{ $nama }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="additionalHarga_1">Harga Additional</label>
                                                        <input type="number" pattern="^[0-9]*$"
                                                            class="form-control additional-price"
                                                            id="additionalPrice-{{ $nama }}-{{ $loop->iteration }}"
                                                            name="additional[]" value="{{ $harga }}" required>
                                                        <small id="helpberat" class="mb-3"
                                                            style="opacity: 75%;">Masukan Tanpa Titik</small>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                        @if (old('additional'))
                                            @for ($i = 0; $i < count(old('additional')); $i += 2)
                                                <div class="additional-input mb-3" id="additional-{{ $i }}">
                                                    <div class="fw-bolder fs-5">Additional <span class="btn"
                                                            onclick="removeAdditionalInput('additional-{{ $i }}')"><i
                                                                class="fas fa-trash"></i></span></div>
                                                    <div class="form-group">
                                                        <label for="additionalName-{{ $i }}">Nama
                                                            Additional</label>
                                                        <input type="text" class="form-control"
                                                            id="additionalName-{{ $i }}" name="additional[]"
                                                            value="{{ old('additional')[$i] }}" required
                                                            @error('add' . $i)
                                                            style="border-color:#D44E4E"
                                                        @enderror>
                                                        @error('add' . $i)
                                                            <div class="text-danger form-text">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="additionalHarga_{{ $i + 1 }}">Harga
                                                            Additional</label>
                                                        <input type="number" pattern="^[0-9]*$"
                                                            class="form-control additional-price"
                                                            id="additionalPrice-{{ $i + 1 }}" name="additional[]"
                                                            value="{{ old('additional')[$i + 1] }}" required
                                                            @error('add' . $i + 1)
                                                            style="border-color:#D44E4E"
                                                        @enderror>
                                                        @error('add' . $i + 1)
                                                            <div class="text-danger form-text">{{ $message }}</div>
                                                        @enderror
                                                        <small class="mb-3" style="opacity: 75%;">Masukan Tanpa
                                                            Titik</small>
                                                    </div>
                                                </div>
                                            @endfor
                                        @endif
                                    </div>
                                    <button type="button" id="addAdditional" class="btn btn-outline"
                                        style="color: #D44E4E">Tambah</button>
                                    <hr class="border border-secondary border-3 my-5">
                                    <!-- Informasi Pengiriman -->
                                    <h5 class="card-title">Informasi Pengiriman</h5>
                                    <div class="mb-3">
                                        <label for="exampleFormControlInput1" class="form-label">Berat Produk<span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="beratProduk" name="beratProduk"
                                            value="{{ $produk->berat_produk }}" pattern="^[0-9]*$" required>
                                        <div id="helperBerat" class="form-text" style="opacity: 50%;">Masukan dalam
                                            satuan
                                            gram. 1000 = 1kg. Tanpa Titik</div>
                                    </div>
                                    <div class="mb-5">
                                        <label class="form-label">Metode Pengiriman<span
                                                class="text-danger">*</span></label><br>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="grab"
                                                value="Grab" name="metode_kirim[]"
                                                {{ in_array('Grab', $produk->metode_kirim ?? []) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="grab">Grab</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="goSend"
                                                value="GoSend" name="metode_kirim[]"
                                                {{ in_array('GoSend', $produk->metode_kirim ?? []) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="goSend">GoSend</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="jne"
                                                value="JNE" name="metode_kirim[]"
                                                {{ in_array('JNE', $produk->metode_kirim ?? []) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="jne">JNE</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="jnt"
                                                value="JNT" name="metode_kirim[]"
                                                {{ in_array('JNT', $produk->metode_kirim ?? []) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="jnt">JNT</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="SiCepat"
                                                value="SiCepat" name="metode_kirim[]"
                                                {{ in_array('SiCepat', $produk->metode_kirim ?? []) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="SiCepat">SiCepat</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="Paxel"
                                                value="Paxel" name="metode_kirim[]"
                                                {{ in_array('Paxel', $produk->metode_kirim ?? []) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="Paxel">Paxel</label>
                                        </div>
                                        <div class="text-danger form-text" style="visibility:hidden;" id="option_error">
                                            Harap
                                            Pilih Metode Kirim</div>
                                    </div>
                                    <div class="d-grid mb-5">
                                        <button class="btn btn-kalasewa btn-lg btn-block" type="submit">Simpan
                                            Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- MODAL HELP INFORASI TAMBAHAN --}}
                    <!-- Modal Grade -->
                    <div class="modal fade" id="infoModalGrade" tabindex="-1" aria-labelledby="infoModalGradeLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="infoModalGradeLabel">Informasi Grade Kostum</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p class="fw-bold">Apa Itu Grade Kostum ?</p>
                                    <p>Sistem grade digunakan untuk membedakan kostum-kostum berdasarkan tingkat
                                        kompleksitas, kualitas, dan harga, serta untuk menentukan siapa yang memenuhi syarat
                                        untuk menyewa setiap grade kostum tersebut.</p>

                                    <p><span class="fw-bold">Jika penyewa
                                            tidak memenuhi syarat,
                                            maka ia tidak akan bisa merental kostum tersebut.</span> Berikut adalah
                                        penjelasan mengenai
                                        sistem grade yang kami terapkan:</p>

                                    <p class="fw-bold">Grade 1</p>
                                    <ul>
                                        <li><span class="fw-bold">Syarat Penyewa :</span> Dapat disewa oleh siapa saja,
                                            termasuk penyewa baru atau newbie.
                                        </li>
                                        <li>Sebaiknya anda memberikan grade 1 untuk kostum yang memiliki brand
                                            standar/low-end, dengan aksesoris yang sedikit. Namun, keputusan penuh ada di
                                            tangan anda untuk menentukan apakah kostum yang anda rental cocok di grade 1
                                            ini.
                                        </li>
                                    </ul>
                                    <p class="fw-bold">Grade 2</p>
                                    <ul>
                                        <li><span class="fw-bold">Syarat Penyewa :</span> Hanya bisa disewa oleh pelanggan
                                            yang sudah memiliki pengalaman menyewa di kalasewa sebelumnya.
                                        </li>
                                        <li>Penyewa baru atau newbie tidak dapat bisa merental kostum dengan grade 2 ini.
                                        </li>
                                    </ul>

                                    <p class="fw-bold">Grade 3</p>
                                    <ul>
                                        <li><span class="fw-bold">Syarat Penyewa :</span> Hanya bisa disewa oleh pelanggan
                                            yang sudah memiliki pengalaman menyewa di kalasewa sebelumnya sebanyak 3x, dan
                                            penyewa memiliki
                                            rating review setidaknya 4. Setiap toko wajib memberikan rating penyewa setiap
                                            selesai penyewaan, sehingga pasti akan ada rating untuk penyewa
                                        </li>
                                        <li>Penyewa baru atau newbie tidak dapat merental kostum dengan grade 3 ini.
                                            Penyewa yang belum memiliki pengalaman di kalasewa, dan
                                            memiliki rating dibawah 4 maka tidak dapat menyewa kostum grade ini.
                                        </li>
                                    </ul>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal Cuci -->
                    <div class="modal fade" id="infoModalCuci" tabindex="-1" aria-labelledby="infoModalCuciLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="infoModalCuciLabel">Informasi Cuci Kostum</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Jika kostum anda memiliki biaya cuci, silahkan tekan ya. <span class="fw-bold">Biaya
                                            cuci akan otomatis
                                            tertambah pada saat penyewa checkout kostum anda. </span> Sehingga nanti harga
                                        kostum + biaya laundry akan dikenakan kepada penyewa</p>
                                    <p>Namun jika anda tidak memiliki biaya ini, maka cukup pilih "Tidak" pada opsi yang
                                        diberikan</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal Wig -->
                    <div class="modal fade" id="infoModalWig" tabindex="-1" aria-labelledby="infoModalWigLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="infoModalWigLabel">Informasi Wig</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p><span class="fw-bold">Jika katalog produk ini sudah termasuk wig, silahkan tekan
                                            "ya" pada opsi.</span> Lalu inputkan informasi brand wig, beserta keterangan
                                        styling wig anda. </p>
                                    <p>Informasi wig ini akan terpampang secara jelas kepada penyewa untuk memberikan
                                        informasi tambahan mengenai apa brand (kualitas) wig yang anda berikan kepada
                                        pelanggan saat penyewaan, dan tipe styling nya</p>
                                    <p>Namun jika anda tidak menyertakan wig pada katalog produk ini, maka cukup pilih
                                        "Tidak" pada opsi yang
                                        diberikan</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('.select2').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            height: $(this).data('height') ? $(this).data('height') : $(this).hasClass('h-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            tags: true,
            createTag: function(params) {
                var term = $.trim(params.term);
                if (term === '') {
                    return null;
                }
                return {
                    id: term,
                    text: term,
                    newTag: true // add additional parameters
                };
            }
        });
    </script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Bootstrap JS (Optional, if you need Bootstrap JS features) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('seller/inputangka.js') }}"></script>
    <script src="{{ asset('seller/inputfotoproduk.js') }}"></script>
@endsection
