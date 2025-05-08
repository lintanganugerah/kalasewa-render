$(document).ready(function () {
    $("#validpassword").on("input", function () {
        let password = $(this).val();
        let regex =
            /^(?=.*[A-Z])(?=.*\d)(?=.*[ -\/:-@\[-`{-~])[A-Za-z\d -\/:-@\[-`{-~]{8,}$/;
        console.log(regex.test(password));

        if (password.length < 8) {
            $("#passwordError").text("Password minimal 8 karakter").show();
        } else if (!regex.test(password)) {
            $("#passwordError")
                .text(
                    "Password harus mengandung minimal satu huruf kapital, satu angka, dan satu simbol"
                )
                .show();
        } else {
            $("#passwordError").hide();
        }
    });

    $("#password_confirmation").on("input", function () {
        let konfirmasi = $(this).val();
        password = $("#validpassword").val();
        if (password != konfirmasi) {
            $("#konfirmasi_error")
                .text("Konfirmasi Password Tidak sama dengan Password")
                .show();
        } else {
            $("#konfirmasi_error").hide();
        }
    });

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
        const NIK = $(this).val();
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
        const kodePos = $(this).val();
        if (kodePos.length < 5) {
            $("#kodePosError").text("Kode pos minimal 5 digit").show();
        } else {
            $("#kodePosError").hide();
        }
    });

    $("#registerForm").on("submit", function (event) {
        let valid = true;

        // Validasi password
        let password = $("#validpassword").val();
        if (password.length < 8) {
            $("#passwordError").text("Password minimal 8 karakter").show();
            valid = false;
        }

        // Validasi NIK
        let NIK = $("#NIK").val();
        if (NIK.length < 16) {
            $("#NIKError").text("NIK minimal 16 karakter").show();
            valid = false;
        }

        // Validasi nomor telpon
        let nomorTelpon = $("#nomor_telpon").val();
        if (nomorTelpon.length < 10) {
            $("#nomorTelponError")
                .text("Nomor telpon minimal 10 karakter")
                .show();
            valid = false;
        }

        // Validasi kode pos
        let kodePos = $("#kodePos").val();
        if (kodePos.length < 5) {
            $("#kodePosError").text("Kode pos minimal 5 karakter").show();
            valid = false;
        }

        let password_confirmation = $("#password_confirmation").val();
        if (password != password_confirmation) {
            $("#konfirmasi_error")
                .text("Konfirmasi Password Tidak sama dengan Password")
                .show();
            $("#validpassword").focus();
            valid = false;
        }

        if (document.getElementById("formFile").files.length == 0) {
            $("#FileError").text("Mohon Isi Foto Identitas ini").show();
            $("#formFile").focus();
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
});
