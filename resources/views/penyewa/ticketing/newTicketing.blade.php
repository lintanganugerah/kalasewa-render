@extends('layout.template')
@extends('layout.navbar')

@section('content')
    <section>

        <div class="container-fluid mt-5">
            <div class="container">
                <div class="card">
                    <div class="card-header text-center">
                        <h1><strong>Ajukan Ticketing Baru</strong></h1>
                    </div>
                    <div class="card-body">

                        <form action="{{ route('createTicket') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-outline mb-4">
                                <label class="form-label" for="jenis_ticketing">Jenis Ticketing<span class="text-danger">*</span></label>
                                <select class="form-select" aria-label="Default select example" name="jenis_ticketing" required>
                                    <option selected disabled>Jenis Ticketing</option>
                                    @foreach ($jenistiket as $kategori)
                                        <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                                    @endforeach
                                </select>
                                <div id="password" class="form-text">Silahkan pilih jenis ticketing yang ingin anda lakukan</div>
                            </div>

                            <div class="form-outline mb-4">
                                <label class="form-label" for="deskripsi_ticketing">Deskripsi Ticketing<span class="text-danger">*</span></label>
                                <textarea id="deskripsi_ticketing" class="form-control form-control-lg" name="deskripsi_ticketing" rows="8" required></textarea>
                                <div id="password" class="form-text">Tuliskan deskripsi ticketing anda secara rinci!</div>
                            </div>

                            <div class="form-outline mb-4">
                                <label for="formFile" class="form-label">Tambahkan Foto<span class="text-danger">*</span></label>
                                <input class="form-control" type="file" id="formFile" name="bukti_tiket[]" accept=".jpg,.png,.jpeg" multiple>
                                <div class="mt-3" id="imageContainer"></div>
                                <div id="emailHelp" class="form-text">Silahkan masukkan gambar bukti permasalahan anda! (Bisa lebih dari 1)</div>
                            </div>

                            <div class="submit-button">
                                <button type="submit" class="btn btn-danger w-100">Submit</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

        @include('layout.footer')

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const fileInput = document.querySelector('input[type="file"][name="bukti_tiket[]"]');
                const imageContainer = document.getElementById('imageContainer');

                fileInput.addEventListener('change', function(event) {
                    const files = event.target.files;
                    imageContainer.innerHTML = ''; // Clear previous images

                    Array.from(files).forEach((file, index) => {
                        const reader = new FileReader();

                        reader.onload = function(e) {
                            const imgContainer = document.createElement('div');
                            imgContainer.style.position = 'relative';
                            imgContainer.style.display = 'inline-block';
                            imgContainer.style.marginRight = '10px';

                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.classList.add('img-thumbnail');
                            img.style.width = '100px'; // Adjust size as needed

                            const removeButton = document.createElement('button');
                            removeButton.innerHTML = '&times;';
                            removeButton.style.position = 'absolute';
                            removeButton.style.top = '5px';
                            removeButton.style.right = '5px';
                            removeButton.style.backgroundColor = 'red';
                            removeButton.style.color = 'white';
                            removeButton.style.border = 'none';
                            removeButton.style.borderRadius = '50%';
                            removeButton.style.cursor = 'pointer';
                            removeButton.dataset.index = index;

                            removeButton.addEventListener('click', function() {
                                // Remove the image from the container
                                imgContainer.remove();
                                // Optionally, you can remove the file from the input as well
                                const dt = new DataTransfer();
                                const {
                                    files
                                } = fileInput;
                                for (let i = 0; i < files.length; i++) {
                                    if (i !== parseInt(removeButton.dataset.index, 10)) {
                                        dt.items.add(files[i]);
                                    }
                                }
                                fileInput.files = dt.files;
                            });

                            imgContainer.appendChild(img);
                            imgContainer.appendChild(removeButton);
                            imageContainer.appendChild(imgContainer);
                        };

                        reader.readAsDataURL(file);
                    });
                });
            });
        </script>

    </section>
@endsection
