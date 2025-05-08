function checkSemua(elem) {
    if (elem.checked) {
        document.getElementById("nominal").value = elem.value;
    }
}
$("body").on("click", ".btnTarik", function () {
    if (validateForm()) {
        $("#modalOTP").modal("show");
        startCountdown();
        handleOtpRequest();
    }
});

$("body").on("click", "#requestOtpButton", function () {
    startCountdown();
    $(".info").text(
        "Kode OTP sedang dalam proses dikirimkan ke email anda. Mohon Tunggu sebentar"
    );
    handleOtpRequest();
});

let otpTimer;

function startCountdown() {
    let remainingTime = 30;
    const requestOtpButton = document.getElementById("requestOtpButton");
    const otpTimerDisplay = document.getElementById("otpTimer");
    let timeLeft = remainingTime;

    if (otpTimer) {
        clearInterval(otpTimer);
    }

    // Disable button dan mulai countdown
    requestOtpButton.disabled = true;
    otpTimerDisplay.textContent = `(${timeLeft} detik)`;

    otpTimer = setInterval(() => {
        timeLeft--;
        otpTimerDisplay.textContent = `(${timeLeft} detik)`;

        if (timeLeft <= 0) {
            clearInterval(otpTimer);
            requestOtpButton.disabled = false;
            otpTimerDisplay.textContent = "";
        }
    }, 1000);
}

function handleOtpRequest() {
    $.ajax({
        url: document
            .querySelector('meta[name="ajax-url"]')
            .getAttribute("content"),
        type: "POST",
        data: {
            _token: document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        success: function (response) {
            // Menampilkan pesan sukses di modal
            if (response.success) {
                $(".info").html(
                    response.message +
                        '<span class="fw-bold text-danger">' +
                        document
                            .querySelector('meta[name="email"]')
                            .getAttribute("content") +
                        "</span>"
                );
            } else if (response.success == false) {
                $(".info").text(response.message);
                $("#requestOtpButton").prop("disabled", false);
            } else {
                $(".info").text("Sedang mengirim kode OTP");
            }
        },
        error: function (status) {
            console.log("Status:", status.statusText); // Menampilkan status kesalahan
            $(".info").html(
                '<span class="text-danger fw-bold">Terjadi kesalahan saat memproses permintaan. Silakan tekan ulang tombol Ajukan Penarikan. </span>' +
                    "status error : " +
                    status.statusText
            );
            clearInterval(otpTimer);
            $("#otpTimer").text("");
            $("#requestOtpButton").prop("disabled", false);
        },
    });
}

function validateForm() {
    const nominalInput = $("#nominal");
    const nominalValue = parseInt(nominalInput.val());
    const minNominal = 10000;
    const maxNominal = parseInt(nominalInput.attr("max"));

    // Cek apakah nilai nominal sesuai dengan kriteria
    if (isNaN(nominalValue)) {
        $("#nominalError").text(`Nilai nominal tidak boleh kosong`);
        nominalInput.addClass("is-invalid");
        return false;
    } else if (nominalValue < minNominal || nominalValue > maxNominal) {
        $("#nominalError").text(
            `Nilai nominal harus antara Rp. ${minNominal} dan Rp. ${maxNominal}`
        );
        nominalInput.addClass("is-invalid");
        return false;
    }

    $("#nominalError").hide();
    nominalInput.removeClass("is-invalid");

    return true;
}

document.getElementById("nominal").addEventListener("input", function (e) {
    // Menghilangkan karakter selain angka dari nilai input
    this.value = this.value.replace(/[^0-9]./g, "");
});
