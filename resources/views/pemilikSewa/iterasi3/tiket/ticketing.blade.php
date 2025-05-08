@extends('layout.selllayout')
@section('content')
<div class="row">
    <div class="col">
        <div class="d-flex justify-content-between mb-5 mt-3">
            <div>
                <h1 class="fw-bold text-secondary">Ticketing</h1>
                <h4 class="fw-semibold text-secondary">Laporkan Permasalahan anda disini</h4>
            </div>
            <div>
                <a href="{{ route('seller.tiket.createTicketing') }}" class="btn btn-kalasewa mt-3">Buat Tiket</a>
            </div>
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
        </ol>

        <div class="table-responsive">
            <table class="table tabel-data no-more-tables" id="series-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kategori</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Deskripsi Masalah</th>
                        <th>Bukti</th>
                        <th>Status</th>
                        <th>Alasan Penolakan</th>
                    </tr>
                </thead>
                @if ($items)
                    <tbody id="series-table">
                        @foreach ($items as $item)
                            <tr>
                                <td width="4%">{{ $loop->iteration }}</td>
                                <td>{{ $item->kategori->nama }}</td>
                                <td>{{ $item->created_at->translatedFormat('d/m/Y') }}</td>
                                <td>{{ $item->deskripsi }}</td>
                                <td>
                                    <a data-bs-toggle="modal" data-bs-target="#modalBukti-{{ $item->id }}"
                                        class="text-danger">Lihat Bukti
                                        Permasalahan</a>
                                </td>
                                <td>
                                    @if ($item->status == 'Menunggu Konfirmasi')
                                        <span class="badge badge-warning">Menunggu Konfirmasi</span>
                                    @elseif ($item->status == 'Sedang Diproses')
                                        <span class="badge badge-info">Sedang Diproses</span>
                                    @elseif ($item->status == 'Selesai')
                                        <span class="badge badge-success">Selesai</span>
                                    @else
                                        <span class="badge badge-danger">Ditolak</span>
                                    @endif
                                </td>
                                <td> {{ $item->alasan_penolakan ? $item->alasan_penolakan : '-' }} </td>
                            </tr>
                            <!-- Modal -->
                            <div class="modal fade" id="modalBukti-{{ $item->id }}" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Bukti Permasalahan</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="d-flex justify-content-start align-items-center">
                                                <div class="row">
                                                    @if ($item->bukti_tiket)
                                                        @foreach ($item->bukti_tiket as $f)
                                                            <div class="col my-2">
                                                                <div class="product-image-container-review">
                                                                    <img src="{{ asset($f) }}" alt="Bukti Tiket"
                                                                        class="product-image-review">
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <p> Tidak ada Foto Bukti </p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                @endif
            </table>
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
@endsection
<link type="text/css" href="{{ asset('seller/review.css') }}" rel="stylesheet">