@extends('admin.layout.app')

@section('title', 'Detail Pengguna')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Detail Pengguna</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.verify.index') }}">Verifikasi User</a></li>
        <li class="breadcrumb-item active" aria-current="page">Detail Pengguna</li>
    </ol>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-5">
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex" style="padding: 10px 20px;">
                        <strong style="flex: 1;">NIK</strong>
                        <span style="flex: 0 0 auto;">: </span>
                        <span style="flex: 2;"> {{ $user->NIK }}</span>
                    </li>
                    <li class="list-group-item d-flex" style="padding: 10px 20px;">
                        <strong style="flex: 1;">Nama</strong>
                        <span style="flex: 0 0 auto;">: </span>
                        <span style="flex: 2;"> {{ $user->nama }}</span>
                    </li>
                    <li class="list-group-item d-flex" style="padding: 10px 20px;">
                        <strong style="flex: 1;">Email</strong>
                        <span style="flex: 0 0 auto;">: </span>
                        <span style="flex: 2;"> {{ $user->email }}</span>
                    </li>
                    <li class="list-group-item d-flex" style="padding: 10px 20px;">
                        <strong style="flex: 1;">Nomor Telepon</strong>
                        <span style="flex: 0 0 auto;">: </span>
                        <span style="flex: 2;"> {{ $user->no_telp }}</span>
                    </li>
                    <li class="list-group-item d-flex" style="padding: 10px 20px;">
                        <strong style="flex: 1;">Nomor Telepon Darurat</strong>
                        <span style="flex: 0 0 auto;">: </span>
                        <span style="flex: 2;"> {{ $user->no_darurat }} ({{$user->ket_no_darurat}})</span>
                    </li>
                    <li class="list-group-item d-flex" style="padding: 10px 20px;">
                        <strong style="flex: 1;">Alamat</strong>
                        <span style="flex: 0 0 auto;">: </span>
                        <span style="flex: 2;"> {{ $user->alamat }}</span>
                    </li>
                    <li class="list-group-item d-flex" style="padding: 10px 20px;">
                        <strong style="flex: 1;">Kode Pos</strong>
                        <span style="flex: 0 0 auto;">: </span>
                        <span style="flex: 2;"> {{ $user->kode_pos }}</span>
                    </li>
                    <li class="list-group-item d-flex" style="padding: 10px 20px;">
                        <strong style="flex: 1;">Kota/Kabupaten</strong>
                        <span style="flex: 0 0 auto;">: </span>
                        <span style="flex: 2;"> {{ $user->kota }}, {{ $user->provinsi }}</span>
                    </li>
                    <li class="list-group-item d-flex" style="padding: 10px 20px;">
                        <strong style="flex: 1;">Role</strong>
                        <span style="flex: 0 0 auto;">: </span>
                        <span style="flex: 2;"> {{ $user->role === 'penyewa' ? 'Penyewa' : 'Pemilik Sewa' }}</span>
                    </li>
                    <li class="list-group-item d-flex" style="padding: 10px 20px;">
                        <strong style="flex: 1;">Link Instagram</strong>
                        <span style="flex: 0 0 auto;">: </span>
                        <span style="flex: 2;"><a href="{{ $user->link_sosial_media }}" target="_blank">
                                {{ $user->link_sosial_media }}</a></span>
                    </li>
                </ul>
                <div class="mt-4 d-flex justify-content-between">
                    <button type="button" class="btn btn-success" style="width: 45%;"
                        onclick="confirmAction('{{ route('admin.users.updateVerification', $user->id) }}', 'verify')">Verifikasi</button>
                    <button type="button" class="btn btn-danger" style="width: 45%;"
                        onclick="confirmAction('{{ route('admin.users.updateVerification', $user->id) }}', 'reject')">Tolak</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        @if ($user->role !== 'pemilik_sewa')
            <div class="text-center mb-4">
                <img id="userFotoProfil"
                    src="{{ $user->foto_profil ? asset($user->foto_diri) : asset('default-profile.png') }}"
                    alt="Foto Profil" class="img-thumbnail" style="max-width: 150px;">
            </div>
        @endif
        <div class="text-center mt-4">
            <img id="userIdentitas" src="{{ $user->foto_identitas ? asset($user->foto_identitas) : 'FOTO NULL' }}"
                alt="Identitas" class="img-thumbnail">
        </div>
    </div>
</div>

<!-- Modal Verifikasi -->
<div class="modal fade" id="confirmActionModal" tabindex="-1" role="dialog" aria-labelledby="confirmActionModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmActionModalLabel">Verifikasi User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin seluruh data diri sudah sesuai?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <form id="actionForm" action="" method="POST" class="d-inline">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="action" id="actionInput">
                    <button type="submit" class="btn btn-success" id="confirmButton">Verifikasi</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Alasan Penolakan -->
<div class="modal fade" id="rejectReasonModal" tabindex="-1" role="dialog" aria-labelledby="rejectReasonModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectReasonModalLabel">Alasan Penolakan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="rejectReasonForm" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="action" value="reject">
                    <p>Pilih alasan penolakan:</p>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="reason" id="reason1"
                            value="Data diri tidak sesuai dengan kartu identitas" required>
                        <label class="form-check-label" for="reason1">
                            Data diri tidak sesuai dengan kartu identitas
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="reason" id="reason2"
                            value="Akun Instagram Private atau Tidak Aktif" required>
                        <label class="form-check-label" for="reason2">
                            Akun Instagram Private atau Tidak Aktif
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="reason" id="reason3"
                            value="Nomor Telepon tidak ada di WhatsApp" required>
                        <label class="form-check-label" for="reason3">
                            Nomor Telepon tidak ada di WhatsApp
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-danger" form="rejectReasonForm">Tolak</button>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmAction(url, action) {
        if (action === 'reject') {
            var rejectForm = document.getElementById('rejectReasonForm');
            rejectForm.action = url;
            $('#rejectReasonModal').modal('show'); // Show the reject reason modal
        } else {
            var actionForm = document.getElementById('actionForm');
            actionForm.action = url; // Set the form action URL
            document.getElementById('actionInput').value = action; // Set the action input value

            var confirmButton = document.getElementById('confirmButton'); // The submit button in the modal
            if (action === 'verify') {
                confirmButton.classList.remove('btn-danger');
                confirmButton.classList.add('btn-success');
                confirmButton.textContent = 'Verifikasi';
            }

            $('#confirmActionModal').modal('show'); // Show the modal
        }
    }
</script>

@endsection