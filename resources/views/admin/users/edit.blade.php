@extends('admin.layout.app')

@section('title', 'Edit User')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit User</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Manajemen User</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit User</li>
        </ol>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <!-- Informasi User -->
                    <div class="col-md-6">
                        <h4>Informasi User</h4>
                        <!-- Common fields for all roles -->
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama"
                                value="{{ $user->nama }}"
                                @if (Auth::check() && (
                                    (Auth::user()->role === 'super_admin' && $user->role === 'admin') || 
                                    Auth::user()->id === $user->id))
                                @else
                                    readonly
                                @endif>
                            @error('nama')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                                value="{{ $user->email }}"
                                @if (Auth::check() && (
                                    (Auth::user()->role === 'super_admin' && $user->role === 'admin') || 
                                    Auth::user()->id === $user->id))
                                @else
                                    readonly
                                @endif>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="no_telp">No. Telepon</label>
                            <input type="tel" class="form-control @error('no_telp') is-invalid @enderror" id="no_telp" name="no_telp"
                                value="{{ $user->no_telp }}"
                                @if (Auth::check() && (
                                    (Auth::user()->role === 'super_admin' && $user->role === 'admin') || 
                                    Auth::user()->id === $user->id))
                                @else
                                    readonly
                                @endif
                                pattern="[0-9]{10,}" title="Hanya boleh angka dengan minimal 10 digit">
                        </div>
                        @if ($user->role == 'penyewa' || $user->role == 'pemilik_sewa')
                            <div class="form-group">
                                <label for="no_darurat">No. Darurat</label>
                                <input type="text" class="form-control" id="no_darurat" name="no_darurat"
                                    value="{{ $user->no_darurat }} ({{ $user->ket_no_darurat }})" readonly>
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="role" style="margin-right: 8px;">Role :</label>
                                @if($user->role == 'super_admin')
                                    <span class="badge badge-success badge-pill px-3 py-2">Super Admin</span>
                                @elseif($user->role == 'admin')
                                    <span class="badge badge-warning badge-pill px-3 py-2">Admin</span>
                                @elseif($user->role == 'penyewa')
                                    <span class="badge badge-primary badge-pill px-3 py-2">
                                        <i class="fas fa-user"></i> Penyewa
                                    </span>
                                @elseif($user->role == 'pemilik_sewa')
                                    <span class="badge badge-primary badge-pill px-3 py-2">
                                        <i class="fas fa-store"></i> Pemilik Sewa
                                    </span>
                                @else
                                    <input type="text" class="form-control" value="{{ $user->role }}" readonly>
                                @endif                          
                        </div>
                        <div class="form-group">
                            <label for="verifyIdentitas" style="margin-right: 8px;">Verifikasi Identitas :</label>
                                @if($user->verifyIdentitas == 'Sudah')
                                    <span class="badge badge-success badge-pill px-3 py-2">Verified</spa>
                                @elseif($user->verifyIdentitas == 'Tidak')
                                    <span class="badge badge-warning badge-pill px-3 py-2">Unverified</span>
                                @elseif($user->verifyIdentitas == 'Ditolak')
                                    <span class="badge badge-danger badge-pill px-3 py-2">Rejected</span>
                                @else
                                    <input type="text" class="form-control" value="{{ $user->verifyIdentitas }}" readonly>
                                @endif
                        </div>
                        @if ($user->role == 'penyewa' || $user->role == 'pemilik_sewa')
                            <div class="form-group">
                                <label for="badge" style="margin-right: 8px;">Status :</label>
                                    @if($user->badge == 'Aktif')
                                        <span class="badge badge-success badge-pill px-3 py-2">
                                        <i class="fas fa-check"></i> Aktif</span>
                                    @elseif($user->badge == 'Banned')
                                        <span class="badge badge-danger badge-pill px-3 py-2">
                                        <i class="fas fa-ban"></i> Banned</span>
                                    @else
                                        <input type="text" class="form-control" value="{{ $user->badge }}" readonly>
                                    @endif
                            </div>
                            <div class="form-group">
                                <label for="link_sosial_media">Link Sosial Media</label>
                                <a href="{{ $user->link_sosial_media }}"
                                    target="_blank">{{ $user->link_sosial_media }}</a>
                            </div>
                        @endif
                    </div>

                    <!-- Identitas User (hanya ditampilkan jika role bukan admin) -->
                    @if ($user->role == 'penyewa' || $user->role == 'pemilik_sewa')
                        <div class="col-md-6">
                            <h4>Identitas User</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="foto_profil">Foto Profil</label>
                                        @if ($user->foto_profil)
                                            <img src="{{ asset($user->foto_profil) }}" alt="Foto Profil"
                                                class="mt-2 d-block" width="150">
                                        @else
                                            <i class="fas fa-portrait fa-10x mt-2 d-block"></i>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="foto_diri">Foto Diri</label>
                                        @if ($user->foto_diri)
                                            <img src="{{ asset($user->foto_diri) }}" alt="Foto Diri" class="mt-2 d-block"
                                                width="150">
                                        @else
                                            <i class="fas fa-portrait fa-10x mt-2 d-block"></i>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="identitas">Identitas</label>
                                        @if ($user->foto_identitas)
                                             <a href="{{ asset($user->foto_identitas) }}" target="_blank">
                                                <img src="{{ asset($user->foto_identitas) }}" alt="Foto Identitas" class="mt-2 d-block" width="250">
                                            </a>
                                        @else
                                            <i class="fas fa-id-card fa-10x mt-2 d-block"></i>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="NIK">NIK</label>
                                <input type="text" class="form-control" id="NIK" name="NIK"
                                    value="{{ $user->NIK }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <input type="text" class="form-control" id="alamat" name="alamat"
                                    value="{{ $user->alamat }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="provinsi">Provinsi</label>
                                <input type="text" class="form-control" id="provinsi" name="provinsi"
                                    value="{{ $user->provinsi }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="kota">Kota</label>
                                <input type="text" class="form-control" id="kota" name="kota"
                                    value="{{ $user->kota }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="kode_pos">Kode Pos</label>
                                <input type="text" class="form-control" id="kode_pos" name="kode_pos"
                                    value="{{ $user->kode_pos }}" readonly>
                            </div>
                        </div>
                    @endif
                </div>

                @if (Auth::check() && (
                    (Auth::user()->role === 'super_admin' && $user->role === 'admin') || 
                    Auth::user()->id === $user->id))
                    <button type="submit" class="btn btn-primary btn-block mb-2">Update</button>
                @else
                @endif
            </form>

            <!-- Tombol Delete -->
            @if (Auth::check())
                @if (Auth::user()->role === 'super_admin' && ($user->role == 'super_admin'))
                    <!-- Super Admin tidak dapat mengklik tombol delete -->
                    <button type="button" class="btn btn-danger delete-btn btn-block mb-2" disabled>Delete</button>
                @elseif (Auth::user()->role === 'super_admin' || Auth::user()->id === $user->id)
                    <!-- Super Admin atau user yang bersangkutan -->
                    <button type="button" class="btn btn-danger delete-btn btn-block mb-2"
                            onclick="confirmDelete('{{ $user->id }}')" data-toggle="modal" data-target="#confirmDeleteModal">Delete</button>
                @elseif (Auth::user()->role === 'admin' && ($user->role == 'penyewa' || $user->role == 'pemilik_sewa'))
                    <!-- Admin yang mengedit penyewa atau pemilik sewa -->
                    <button type="button" class="btn btn-danger delete-btn btn-block mb-2"
                            onclick="confirmDelete('{{ $user->id }}')" data-toggle="modal" data-target="#confirmDeleteModal">Delete</button>
                @endif
            @endif


            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-block">Kembali</a>
        </div>
    </div>

    <!-- Modal Konfirmasi Delete -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Konfirmasi Delete User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus user ini?
                    <br>
                <small style="color:red">Tindakan ini tidak dapat diurungkan!</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>

        </form>

    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.querySelector('.toggle-password');
        const password = document.getElementById('password');

        if (togglePassword) {
            togglePassword.addEventListener('click', function() {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });
        }
    });


    function confirmDelete() {
        return confirm('Apakah Anda yakin ingin menghapus user ini?');
    }

</script>
@endsection
