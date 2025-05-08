@extends('admin.layout.app')

@section('title', 'Kategori Tiket')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Kategori Tiket</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Kategori Tiket</li>
    </ol>
</div>

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
@if ($errors->has('error'))
    <div class="alert alert-danger">
        {{ $errors->first('error') }}
    </div>
@endif

<!-- Tambah Kategori Button -->
<button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addCategoryModal">
    Tambah Kategori
</button>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.ticket.category.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel">Tambah Kategori</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama">Nama Kategori</label>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                            name="nama" required>
                        @error('nama')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card mb-5">
    <div class="card-body">
        <h5 class="card-title">Daftar Kategori Tiket</h5>
        <div class="table-responsive">
            @if ($categories->isEmpty())
                <p>Tidak ada kategori tiket.</p>
            @else
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center align-middle">Nama</th>
                            <th colspan="2" class="text-center align-middle" style="width:15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td>{{ $category->nama }}</td>
                                <td class="text-center align-middle">
                                    <a href="{{ route('admin.ticket.editCategory', $category->id) }}"
                                        class="btn btn-warning">Edit</a>
                                    <button type="button" class="btn btn-danger" data-toggle="modal"
                                        data-target="#deleteCategoryModal-{{$category->id}}">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                            
                            <!-- Delete Confirmation Modal -->
                            <div class="modal fade" id="deleteCategoryModal-{{$category->id}}" tabindex="-1" aria-labelledby="deleteCategoryModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteCategoryModalLabel">Konfirmasi Hapus</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Apakah Anda yakin ingin menghapus kategori '{{$category->nama}}'?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                            <form id="deleteForm{{ $category->id }}"
                                                action="{{ route('admin.ticket.category.delete', $category->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>

@endsection