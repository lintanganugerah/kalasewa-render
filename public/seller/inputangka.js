// Tambahkan event listener untuk memantau input pengguna
document.getElementById("nomor_telpon").addEventListener("input", function (e) {
    // Menghilangkan karakter selain angka dari nilai input
    this.value = this.value.replace(/[^0-9]/g, "");
});

document.getElementById("kodePos").addEventListener("input", function (e) {
    // Menghilangkan karakter selain angka dari nilai input
    this.value = this.value.replace(/[^0-9]/g, "");
});

document.getElementById("NIK").addEventListener("input", function (e) {
    // Menghilangkan karakter selain angka dari nilai input
    this.value = this.value.replace(/[^0-9]/g, "");
});
