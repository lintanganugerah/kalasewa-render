function toggleDendaOptions() {
    const dendaYa = document.getElementById("dendaYa").checked;

    if (dendaYa) {
        const optionDiv = document.getElementById("options");
        const optionNew = document.createElement("div");
        optionNew.classList.add("mb-2");
        optionNew.id = `optionsDendaPasti`;
        optionNew.innerHTML = `
                    <div class="mb-3">
                        <label class="form-label">Apakah denda memiliki nominal pasti?</label><a data-bs-toggle="modal"
                                                        data-bs-target="#infoModal"><i class="fa-solid fa-regular fa-circle-info ms-2"></i></a><br>
                        <input type="radio" id="hargaPastiTidak" name="DendaPasti" value="tidak"
                            onchange="toggleDendaPastiOptions()"> Tidak
                        <input type="radio" id="DendaPastiYa" name="DendaPasti" value="ya"
                            onchange="toggleDendaPastiOptions()"> Ya
                    </div>`;
        optionDiv.appendChild(optionNew);
    } else {
        removeDivId("optionsDendaPasti");
    }
}

function removeDivId(id) {
    document.getElementById(id).remove();
}

function toggleDendaPastiOptions() {
    const DendaPastiYa = document.getElementById("DendaPastiYa").checked;

    if (DendaPastiYa) {
        const optionDiv = document.getElementById("optionsDendaPasti");
        const fieldDendaNominal = document.createElement("div");
        fieldDendaNominal.classList.add("Input-denda", "mb-2");
        fieldDendaNominal.id = `DendaPastiNominal`;
        fieldDendaNominal.innerHTML = `
                    <label for="hargaInput" class="form-label">Denda<span
                            class="text-danger mb-3">*</span></label>
                    <small id="helpKeterangan" class="mb-3" style="opacity: 75%;">Jika denda memiliki rentang harga, harap pilih opsi "tidak" pada pertanyaan "Apakah denda memiliki nominal pasti?"</small>
                    <div class="input-group">
                        <span class="input-group-text" id="span_nominal">Rp.</span>
                        <input type="number" id="hargaInput" class="form-control"
                            name="nominal_denda" placeholder="20000" aria-label="Denda"
                            pattern="[0-9]*" required>
                    </div>
                    <small id="helpnominal" class="mb-3" style="opacity: 75%;">Masukan Angka Tanpa
                        Titik. Contoh : 20000</small>`;
        optionDiv.appendChild(fieldDendaNominal);
        removeDivId("DendaPastiDeskriptif");
    } else {
        const optionDiv = document.getElementById("optionsDendaPasti");
        const fieldDenda = document.createElement("div");
        fieldDenda.classList.add("Input-denda", "mb-2");
        fieldDenda.id = `DendaPastiDeskriptif`;
        fieldDenda.innerHTML = `
                    <label for="hargaInput" class="form-label">Denda<span
                        class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" class="form-control"
                            name="nominal_denda" placeholder='Contoh : "Denda sesuai dengan harga baru barang" atau "Rp.50.000 - Rp.100.000"' aria-label="Denda" required>
                        </div>
                    <small id="helpKeterangan" class="mb-3" style="opacity: 75%;">Masukan secara deskripsi/teks. Jika nominal sudah pasti, pilih opsi "Ya" pada button diatas field denda ini</small>`;
        // Menambahkan elemen baru ke dalam elemen dengan ID "photoInputs"
        optionDiv.appendChild(fieldDenda);
        removeDivId("DendaPastiNominal");
    }
}
