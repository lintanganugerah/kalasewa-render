@extends('admin.layout.app')

@section('title', 'Ubah Sandi')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Ubah Sandi</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Ubah Sandi</li>
    </ol>
</div>

<div class="card">
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.updateSandi') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="current_password">Kata Sandi Lama<span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                        id="current_password" name="current_password" placeholder="Masukkan Kata Sandi Lama"
                        value="{{ old('current_password') }}" required>
                    @error('current_password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <label for="password">Kata Sandi Baru<span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                        name="password" placeholder="Masukkan Kata Sandi Baru" value="{{ old('password') }}" required>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Kata Sandi Baru<span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                        id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi Kata Sandi Baru"
                        required>
                    @error('password_confirmation')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="show-passwords" onclick="togglePassword()">
                <label class="form-check-label" for="show-passwords">Tampilkan kata sandi</label>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

<script>
    function togglePassword() {
        const passwordInputs = [
            document.getElementById('current_password'),
            document.getElementById('password'),
            document.getElementById('password_confirmation')
        ];
        const showPasswords = document.getElementById('show-passwords');
        passwordInputs.forEach(input => {
            input.type = showPasswords.checked ? 'text' : 'password';
        });
    }
</script>

@endsection