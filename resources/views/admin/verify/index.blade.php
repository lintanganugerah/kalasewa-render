@extends('admin.layout.app')

@section('title', 'Verifikasi Pengguna')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Verifikasi User</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Verifikasi User</li>
    </ol>
</div>

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

<div class="card mb-5">
    <div class="card-body">
        <h5 class="card-title">Pending Request</h5>
        <div class="table-responsive">
            @if ($users->isEmpty())
                <p>Sedang tidak ada permintaan verifikasi tertunda.</p>
            @else
                <table class="table table-bordered" id="users-table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            @if ($user->role !== 'admin' && $user->verifyIdentitas !== 'Ditolak')
                                <tr>
                                    <td>{{ $user->nama }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if ($user->role === 'penyewa')
                                            Penyewa
                                        @elseif ($user->role === 'pemilik_sewa')
                                            Pemilik Sewa
                                        @endif
                                    </td>
                                    <td style="width: 10%;">
                                        <a href="{{ route('admin.users.show', $user->id) }}"
                                            class="btn btn-primary btn-block">Tampilkan</a>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>

@endsection