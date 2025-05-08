// Buat menghitung foto input yang ditambahkan, nanti jadi ID unik nya
let fotoTambah = 0;

// Setiap button addphotobtn di klik maka fungsi ini jalan
document.getElementById("addPhotoBtn").addEventListener("click", function () {
  fotoTambah++;
  // Mendapatkan elemen id "photoInputs" di view, untuk naroh input baru nya nanti
  const photoInputs = document.getElementById("photoInputs");
  // Membuat elemen div baru untuk input foto baru
  const newPhotoInput = document.createElement("div");
  // Menambahkan class bootstrap dan photo-input
  newPhotoInput.classList.add("photo-input", "mb-2");
  // Memberikan ID unik ke elemen baru
  newPhotoInput.id = `fototambah-${fotoTambah}`;
  // Mengatur isi HTML dari div baru
  newPhotoInput.innerHTML = `
        <div class="d-flex align-items-start">
            <div class="me-3">
                <img class="img-thumbnail" src="" alt="Foto" style="width: 150px; height: 150px; object-fit: cover;">
                <span class="btn" onclick="removeTambahFoto('fototambah-${fotoTambah}')"><i class="fas fa-trash"></i></span>
            </div>
        </div>
        <div class="flex-grow-1">
            <input type="file" name="foto_produk[]" class="form-control userPhoto" id="fotoInput-${fotoTambah}" accept=".jpg,.png,.jpeg,.webp">
            <div class="form-text error-message text-danger" id="FileError-${fotoTambah}"></div>
        </div>`;
  // Menambahkan elemen baru ke dalam elemen dengan ID "photoInputs"
  photoInputs.appendChild(newPhotoInput);

  // Tambahkan event listener untuk perubahan pada input file di dalam div "photoInputs". Ini di eksekusi khusus div yang ditambahkan baru
  photoInputs.addEventListener("change", function (event) {
    // Mendapatkan elemen div foto terdekat dari target event, target event nya adalah elemen input yang menyebabkan peristiwa change, yaitu photo-input.
    const photoInputDiv = event.target.closest(".photo-input");
    // Panggil fungsi cekSizedanTampilkanImage untuk memproses input file yang berubah
    cekSizedanTampilkanImage(event, photoInputDiv);
  });
});

// Fungsi untuk validasi ukuran file dan menampilkan gambar
function cekSizedanTampilkanImage(event, photoInputBaru) {
  // Mendapatkan file yang dipilih dari input file
  const file = event.target.files[0];
  // Mendapatkan elemen img dari div photoInputBaru
  const img = photoInputBaru.querySelector("img");
  // Mendapatkan elemen error-message dari div photoInputBaru
  const errorDiv = photoInputBaru.querySelector(".error-message");
  // Membuat objek FileReader untuk membaca konten file
  const reader = new FileReader();

  // Memeriksa ukuran file sebelum memuat gambar
  if (file.size > 5 * 1024 * 1024) {
    // Menghitung 5MB dalam bentuk bytes, karena js menerima bytes
    // Jika ukuran file lebih dari 5MB, tampilkan pesan error
    errorDiv.textContent =
      "Ukuran file tidak boleh lebih dari 5MB. Silahkan Upload Ulang";
    // Mengosongkan nilai input file
    event.target.value = "";
    // Mengosongkan gambar preview
    img.src = "";
    return;
  } else {
    // Jika ukuran file valid, hapus pesan error yang ada
    errorDiv.textContent = "";
  }

  // Callback untuk membaca data URL dari file yang dipilih
  reader.onload = function (e) {
    // Menampilkan data URL sebagai sumber gambar di elemen img
    img.src = e.target.result;
  };

  // Membaca file sebagai data URL
  reader.readAsDataURL(file);
}

// Memilih semua elemen input file di halaman
fileInputs = document.querySelectorAll('input[type="file"]');

// Menambahkan event listener untuk perubahan pada setiap input file yang ada
fileInputs.forEach((input) => {
  input.addEventListener("change", function (event) {
    // Mendapatkan elemen div foto terdekat dari target event
    const photoInputDiv = event.target.closest(".photo-input");
    // Panggil fungsi cekSizedanTampilkanImage untuk memproses input file yang berubah
    cekSizedanTampilkanImage(event, photoInputDiv);
  });
});

function removeTambahFoto(id) {
  document.getElementById(id).remove();
}

document.getElementById("hargaInput").addEventListener("input", function (e) {
  // Menghilangkan karakter selain angka dari nilai input
  this.value = this.value.replace(/[^\d]/g, "");
});

document.getElementById("beratProduk").addEventListener("input", function (e) {
  // Menghilangkan karakter selain angka dari nilai input
  this.value = this.value.replace(/[^\d]/g, "");
});

$("#formproduk").on("submit", function (event) {
  if ($('input[name="metode_kirim[]"]:checked').length < 1) {
    event.preventDefault();
    document.getElementById("option_error").style.visibility = "visible";
  } else {
    document.getElementById("option_error").style.visibility = "hidden";
  }
});

function opsiCuci() {
  var cuciYa = document.getElementById("cuciYa").checked;

  if (cuciYa) {
    var optionDiv = document.getElementById("optionsBiayaCuci");
    var optionNew = document.createElement("div");
    optionNew.classList.add("mb-2");
    optionNew.id = `optionsCuci`;
    optionNew.innerHTML = `
                    <label for="biaya_cuci" class="form-label">Biaya Cuci<span
                            class="text-danger mb-3">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text" id="span_nominal">Rp.</span>
                        <input type="number" id="biaya_cuci" class="form-control"
                            name="biaya_cuci" placeholder="20000" aria-label="cuci"
                            pattern="[0-9]*" required>
                    </div>
                    <small id="helpnominal" class="mb-3" style="opacity: 75%;">Masukan Angka Tanpa
                        Titik. Contoh : 20000</small>`;
    optionDiv.appendChild(optionNew);
    document
      .getElementById("biaya_cuci")
      .addEventListener("input", function (e) {
        // Menghilangkan karakter selain angka dari nilai input
        this.value = this.value.replace(/[^0-9]./g, "");
      });
  } else {
    document.getElementById("optionsCuci").remove();
  }
}

function opsiWig() {
  var wigYa = document.getElementById("wigYa").checked;

  if (wigYa) {
    var optionDiv = document.getElementById("optionsWig");
    var optionNew = document.createElement("div");
    optionNew.classList.add("mb-2");
    optionNew.id = `infoWig`;
    optionNew.innerHTML = `
                    <div class="mb-3">
                        <label for="brand_wig" class="form-label">Brand wig<span
                                class="text-danger mb-3">*</span></label>
                        <input type="text" id="brand_wig" class="form-control"
                        name="brand_wig" aria-label="brand_wig" required>
                        <small id="helpnominal" class="mb-3" style="opacity: 75%;">Masukan No Brand jika tidak ada brand</small>
                    </div>
                    <div class="mb-3">
                        <label for="ket_wig" class="form-label">Keterangan Styling Wig<span class="text-danger mb-3">*</span></label>
                        <input type="text" id="ket_wig" placeholder="Soft Styling/Hard Styling/No Styling" class="form-control" name="ket_wig" aria-label="ket_wig" required>
                    </div>`;
    optionDiv.appendChild(optionNew);
  } else {
    document.getElementById("infoWig").remove();
  }
}

let additionalCount = 0;

document.getElementById("addAdditional").addEventListener("click", function () {
  additionalCount++;

  const inputContainer = document.getElementById("additionalInputs");
  const newInput = document.createElement("div");
  newInput.classList.add("additional-input", "mb-3");
  newInput.id = `additional-${additionalCount}`;
  newInput.innerHTML = `
    <div class="fw-bolder fs-5">Additional <span class="btn" onclick="removeAdditionalInput('additional-${additionalCount}')"><i class="fas fa-trash"></i></span></div>
    <div class="form-group">
    <label for="additionalName-${additionalCount}">Nama Additional</label>
    <input type="text" class="form-control" id="additionalName-${additionalCount}" name="additional[]" required>
    </div>
    <div class="form-group">
                <label for="additionalHarga_${additionalCount}">Harga Additional</label>
                <input type="number" pattern="^[0-9]*$" class="form-control additional-price" id="additionalPrice-${additionalCount}" name="additional[]" required>
                <small id="helpberat" class="mb-3" style="opacity: 75%;">Masukan Tanpa
                Titik
                </small>
                </div>`;
  inputContainer.appendChild(newInput);
});

document.getElementById("biaya_cuci").addEventListener("input", function (e) {
  // Menghilangkan karakter selain angka dari nilai input
  this.value = this.value.replace(/[^0-9]./g, "");
});

function removeAdditionalInput(id) {
  document.getElementById(id).remove();
}

// function beriTitik(input) {
//     // Menghilangkan selain angka yang ada
//     input.value = input.value.replace(/[^0-9]/g, "");
//     // Menambahkan titik setiap tiga digit
//     input.value = input.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
// }

// document.addEventListener("DOMContentLoaded", function () {
//     var hargaInput = document.getElementById("hargaInput");
//     var beratProduk = document.getElementById("beratProduk");

//     // Format nilai input saat halaman dimuat
//     beriTitik(hargaInput);
//     beriTitik(beratProduk);

//     // Tambahkan event listener untuk memformat nilai saat diinput
//     hargaInput.addEventListener("input", function () {
//         beriTitik(this);
//     });

//     beratProduk.addEventListener("input", function () {
//         beriTitik(this);
//     });

//     // Hapus titik sebelum form disubmit
//     document
//         .getElementById("formproduk")
//         .addEventListener("submit", function (e) {
//             hargaInput.value = hargaInput.value.replace(/\./g, "");
//             beratProduk.value = beratProduk.value.replace(/\./g, "");
//         });
// });

document.querySelectorAll(".userPhoto").forEach((input) => {
  input.addEventListener("change", function (event) {
    const file = event.target.files[0];
    const img = event.target.closest(".photo-input").querySelector("img");
    const reader = new FileReader();

    reader.onload = function (e) {
      img.src = e.target.result;
    };

    reader.readAsDataURL(file);
  });
});

function removeAdditionalInput(id) {
  document.getElementById(id).remove();
}

// document.addEventListener("DOMContentLoaded", function () {
//     // Mendapatkan semua kotak centang
//     var checkboxes = document.querySelectorAll(".ukuran-checkbox");

//     // Menambahkan event listener untuk setiap kotak centang
//     checkboxes.forEach(function (checkbox) {
//         checkbox.addEventListener("change", function () {
//             const targetId = this.dataset.target;
//             const inputContainer = document.getElementById("additionalInputs");

//             if (this.checked) {
//                 console.log("Kotak centang " + targetId + " telah dicentang");
//                 const newInput = document.createElement("div");
//                 newInput.id = "Inputan_" + targetId; // Setel id agar dapat dihapus nantinya
//                 newInput.classList.add("mb-3", "ukuran-input");
//                 newInput.innerHTML = `
//                             <div class="form-outline">
//                                 <label class="form-label" id="ukuranlabel_${targetId}">Stok Ukuran ${targetId}</label>
//                             </div>
//                            <div class="input-group mb-3">
//                                 <div class="form-floating">
//                                     <input type="text" id="stok_${targetId}" class="form-control" name="stok_${targetId}" pattern="[0-9]*" placeholder="Stok" required>
//                                     <label for="stok_${targetId}" id="stoklabel_${targetId}">Stok</label>
//                                 </div>
//                             </div>`;
//                 inputContainer.appendChild(newInput);
//             } else {
//                 console.log("Kotak centang " + targetId + " telah diuncheck");
//                 const inputRemoveAll = document.getElementById(
//                     "Inputan_" + targetId
//                 );
//                 inputRemoveAll.remove();
//             }
//         });
//     });
// });
