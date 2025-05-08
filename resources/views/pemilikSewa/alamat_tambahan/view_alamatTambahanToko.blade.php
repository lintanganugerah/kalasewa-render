@extends('layout.selllayout')
@section('content')
    <div class="row">
        <div class="col">
            <div class="text-left mb-5 mt-3">
                <h1 class="fw-bold text-secondary">Profil Toko</h1>
                <h4 class="fw-semibold text-secondary">Manajemen Informasi Toko Anda</h4>
            </div>

            <div class="row gx-5">

                <div class="card">
                    <div class="card-header">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link text-secondary" id="profil-tab"
                                    onclick="window.location.href='/profil/toko'" type="button" role="tab"
                                    aria-controls="profil" aria-selected="true">Profil</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active text-secondary fw-bold" id="profil-tab"
                                    onclick="window.location.href='/profil/toko/AlamatTambahan'" type="button"
                                    role="tab" aria-selected="false">Alamat Lainnya</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link text-secondary" id="contact-tab"
                                    onclick="window.location.href='/profil/toko/peraturansewa'" type="button"
                                    role="tab" aria-selected="false">Peraturan
                                    Sewa Toko
                                    Anda</button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="profil" role="tabpanel"
                                aria-labelledby="profil-tab">
                                <div class="row d-flex align-items-center">
                                    <div class="col">
                                        <h5>Informasi Alamat Lainnya</h5>
                                        <div id="helpberat" class="mb-3" style="opacity: 75%;">Jika produk/kostum anda
                                            tersebar di berbagai lokasi, anda bisa menambahkan alamat baru disini. Nantinya
                                            anda bisa memilih lokasi produk/kostum anda berada di alamat yang mana</div>
                                    </div>
                                    <div class="col text-end">
                                        <!-- Button trigger modal -->
                                        <a type="button" class="btn btn-kalasewa"
                                            href="{{ route('seller.profil.viewTambahAlamatTambahan') }}">
                                            Tambah Alamat
                                        </a>
                                    </div>
                                </div>
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
                                <div class="text-dark rounded-3 mt-4">
                                    <table id="tabel" class="no-more-tables table w-100 tabel-data align-items-center"
                                        style="word-wrap: break-word;">
                                        @if ($alamatTambahan)
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th class="col-2">Nama Alamat</th>
                                                    <th class="col-7">Alamat Lengkap</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($alamatTambahan as $at)
                                                    <tr>
                                                        <td data-title="#" class="align-middle">{{ $loop->iteration }}
                                                        </td>
                                                        <td data-title="Nama Alamat" class="align-middle">
                                                            {{ $at->nama }}
                                                        </td>
                                                        <td data-title="Alamat Lengkap" class="align-middle">
                                                            {{ $at->alamat }}, {{ $at->kota }}, {{ $at->provinsi }},
                                                            {{ $at->kode_pos }}
                                                        </td>
                                                        <td data-title="Aksi" class="align-middle">
                                                            <a type="button"
                                                                href="{{ route('seller.profil.viewEditAlamatTambahan', $at->id) }}"
                                                                class="d-grid btn btn-outline-primary m-2"
                                                                id="proses">Edit</a>
                                                            <form
                                                                action="{{ route('seller.profil.DeleteAlamatTambahanAction', $at->id) }}"
                                                                method="POST" class="d-grid m-2"
                                                                onsubmit="return confirm('Jika ada produk dengan alamat ini, maka akan berpindah ke alamat utama toko! Apakah anda yakin ingin menghapus?')">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="btn btn-outline-danger">Hapus</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('.tabel-data').DataTable({
            lengthMenu: [
                [5, 10, 25, -1],
                [5, 10, 25, 'All']
            ],
        });
    </script>
    <script src="{{ asset('seller/validasiProfilToko.js') }}"></script>
    <script src="{{ asset('seller/inputangka.js') }}"></script>
@endsection
