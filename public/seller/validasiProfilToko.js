$("#formFile").change(function () {
    var fileInput = $(this);
    var maxSize = 5 * 1024 * 1024; // 5MB dalam bytes, karena js menerima nya bytes

    // Reset pesan error setiap kali ada perubahan pada input file
    $("#FileError").text("Ukuran file tidak boleh lebih dari 5MB.").hide();

    // Periksa apakah ada file yang dipilih
    if (fileInput.get(0).files.length) {
        var fileSize = fileInput.get(0).files[0].size; // Mendapatkan ukuran file dalam bytes

        // Periksa ukuran file
        if (fileSize > maxSize) {
            // Menampilkan pesan error di bawah input file
            $("#FileError")
                .text("Ukuran file tidak boleh lebih dari 5MB.")
                .show();

            // Mengosongkan nilai input file untuk memaksa pengguna memilih ulang
            fileInput.val("");
        }
    }
});

$("#NIK").on("input", function () {
    var NIK = $(this).val();
    if (NIK.length < 16) {
        $("#NIKError").text("NIK harus 16 digit").show();
    } else if (NIK.length > 16) {
        $("#NIKError")
            .text("NIK harus 16 digit. Sekarang anda lebih dari 16 digit")
            .show();
    } else {
        $("#NIKError").hide();
    }
});

$("#kodePos").on("input", function () {
    var kodePos = $(this).val();
    if (kodePos.length != 5) {
        $("#kodePosError").text("Kode Pos harus 5 digit").show();
    } else {
        $("#kodePosError").hide();
    }
});

$("#formToko").on("submit", function (event) {
    let valid = true;

    // Validasi nomor telpon
    let nomorTelpon = $("#nomor_telpon").val();
    if (nomorTelpon.length < 10) {
        $("#nomorTelponError").text("Nomor telpon minimal 10 karakter").show();
        valid = false;
    }

    // Validasi kode pos
    let kodePos = $("#kodePos").val();
    if (kodePos.value.length < 5) {
        kodePos.focus();
        valid = false;
    }

    if (!valid) {
        event.preventDefault();
    }
});

document.querySelectorAll(".error-check").forEach((input) => {
    input.addEventListener("input", function () {
        // Hapus style merah
        this.style.borderColor = "";

        // Hapus pesan error terkait
        const errorDiv = document.querySelector(
            `.error-message[data-milik="${this.name}"]`
        );
        if (errorDiv) {
            errorDiv.innerText = "";
        }
    });
});

// Menambahkan event listener untuk input pada setiap input kodePos class yang ada
// document.querySelectorAll(".kodePos").forEach((input) => {
//     input.addEventListener("input", function (event) {
//         // Mendapatkan elemen div foto terdekat dari target event
//         this.value = this.value.replace(/[^0-9]/g, "");
//         var value = this.value;
//         var kodePosError =
//             this.closest(".form-outline").querySelector(".error-message");
//         if (value.length < 5) {
//             kodePosError.textContent = "Kode pos minimal 5 digit";
//             kodePosError.style.display = "block";
//         } else {
//             kodePosError.style.display = "none";
//         }
//     });
// });

// var alamatCount = 0;
// document.getElementById("addAlamat").addEventListener("click", function () {
//     alamatCount++;

//     const inputContainer = document.getElementById("alamatInput");
//     const newInput = document.createElement("div");
//     const date = Date.now();
//     newInput.classList.add("alamat-input", "mb-3");
//     newInput.id = `alamat-${date}`;
//     newInput.innerHTML = `
//                 <div class="fw-bolder fs-5">Alamat Baru<span class="btn" onclick="removeAlamatInput('alamat-${date}')"><i class="fas fa-trash"></i></span></div>
//                 <div class="mb-3">
//                     <input type="hidden" class="form-control" name="idTambahan[]"
//                                 pattern="[0-9]*" value="${date}"
//                                 required />
//                     <label for="alamatName-${date}">Nama Alamat</label>
//                     <input type="text" class="form-control" id="alamatName-${date}" name="alamatNameTambahan[]" required>
//                     <small id="helpNama" class="mb-3"
//                         style="opacity: 75%;">Masukan nama alamat yang anda ingat.
//                         Misal: Rumah/Kantor/Rumah Budi
//                     </small>
//                 </div>
//                 <div class="mb-3">
//                     <label for="alamat-${date}">Alamat</label>
//                     <textarea type="text" class="form-control alamat-" id="alamat-${date}" rows="10" name="alamatTambahan[]" required></textarea>
//                     <small id="helpberat" class="mb-3" style="opacity: 75%;">Masukan Tanpa
//                     Titik
//                     </small>
//                 </div>
//                 <div class="mb-3">
//                     <div data-mdb-input-init class="form-outline">
//                         <label class="form-label" for="kodePos">Kode Pos<span
//                                 class="text-danger">*</span></label>
//                         <input type="text" class="form-control kodePos" name="kodePosTambahan[]"
//                             pattern="[0-9]*" minlength="5" maxlength="5"
//                             required />
//                         <div class="form-text error-message text-danger" id="kodePosError"></div>
//                     </div>
//                 </div>
//                 <div class="mb-3">
//                     <label for="exampleFormControlTextarea1" class="form-label">Provinsi<span
//                             class="text-danger">*</span></label>
//                     <select class="form-select" id="floatingSelect" name="provinsiTambahan[]"
//                         aria-label="Floating select example" required>
//                         <option value="Jawa Barat">
//                             Jawa Barat</option>
//                     </select>
//                 </div>
//                 <div class="mb-3">
//                     <label for="exampleFormControlTextarea1" class="form-label">Kota/Kabupaten<span
//                             class="text-danger">*</span></label>
//                     <select class="form-select" id="floatingSelect" name="kotaTambahan[]"
//                         aria-label="Floating label select example" required>
//                         <option disabled selected></option>
//                         <option value="Kota Bandung">Kota Bandung</option>
//                         <option value="Kabupaten Bandung">Kabupaten
//                             Bandung
//                         </option>
//                     </select>
//                 </div>`;
//     inputContainer.appendChild(newInput);

//     document.querySelectorAll(".kodePos").forEach((input) => {
//         input.addEventListener("input", function (event) {
//             this.value = this.value.replace(/[^0-9]/g, "");
//             var value = this.value;
//             var kodePosError =
//                 this.closest(".form-outline").querySelector(".error-message");
//             if (value.length < 5) {
//                 kodePosError.textContent = "Kode pos minimal 5 digit";
//                 kodePosError.style.display = "block";
//             } else {
//                 kodePosError.style.display = "none";
//             }
//         });
//     });
// });

// function removeAlamatInput(id) {
//     document.getElementById(id).remove();
// }
