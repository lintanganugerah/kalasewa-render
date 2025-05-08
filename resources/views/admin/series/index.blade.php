@extends('admin.layout.app')

@section('title', 'Dashboard')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Manajemen Series</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Manajemen Series</li>
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

<div class="row">
    <div class="col-md-6 mb-3">
        <a href="{{ route('admin.series.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle mr-2"></i> Tambah Series
        </a>
    </div>
    <div class="col-md-6 mb-3 d-flex justify-content-end">
        <form action="{{ route('admin.series.index') }}" class="form-inline" method="GET">
            <input type="search" name="search" class="form-control mr-2" placeholder="Search"
                value="{{ request('search') }}">
            <button type="submit" class="btn btn-default">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>
</div>

<div class="table-responsive">
    @if ($series->isEmpty())
        <div class="text-center mt-5">
            <p class="text-muted">Series tidak ditemukan.</p>
        </div>
    @else
        <table class="table table-data" id="series-table">
            <thead>
                <tr>
                    <th>Nama Series</th>
                    <th colspan="2" style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($series as $seriesItem)
                    <tr>
                        <td>{{ $seriesItem->series }}</td>
                        <td width="8%">
                            <a href="{{ route('admin.series.edit', $seriesItem->id) }}"
                                class="btn btn-warning btn-block">Edit</a>
                        </td>
                        <td width="8%">
                            <button class="btn btn-danger btn-block" data-toggle="modal"
                                data-target="#confirmDeleteModal{{ $seriesItem->id }}">Delete</button>
                        </td>
                    </tr>

                    <!-- Modal Konfirmasi Delete -->
                    <div class="modal fade" id="confirmDeleteModal{{ $seriesItem->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="confirmDeleteModalLabel{{ $seriesItem->id }}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="confirmDeleteModalLabel{{ $seriesItem->id }}">Konfirmasi Delete
                                        Series</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    Apakah Anda yakin ingin menghapus series ini?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                    <form id="deleteForm{{ $seriesItem->id }}"
                                        action="{{ route('admin.series.destroy', $seriesItem->id) }}" method="POST"
                                        class="d-inline">
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

    <div style="display: flex; justify-content: center; margin: 20px 0;">
        {{ $series->appends(request()->query())->links() }}
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        $('.btn-danger').on('click', function () {
            var seriesId = $(this).closest('tr').find('.series-id').text();
            var action = $('#deleteForm' + seriesId).attr('action');
            $('#deleteForm' + seriesId).attr('action', action + '/' + seriesId);
        });
    });
</script>
@endsection