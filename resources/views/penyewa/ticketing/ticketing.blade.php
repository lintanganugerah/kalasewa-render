@extends('layout.template')
@extends('layout.navbar')

@section('content')

<section>

    <div class="container-fluid mt-5">
        <div class="container">

            <div class="text-center">
                <h1><strong>Ticketing Kalasewa</strong></h1>
            </div>

            @csrf
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="card mt-5">
                <div class="card-body">

                    <div class="create-button">
                        <div class="col-2">
                            <a href="{{ route('viewNewTicketing') }}" class="btn btn-danger w-100">Ajukan Tiket Baru</a>
                        </div>
                    </div>

                    <div class="table-ticketing mt-3">
                        <table class="table w-100" id="table-ticketing">
                            @if ($ticketing)
                                <thead>
                                    <tr>
                                        <td class="text-center fw-bold">Nomor Tiket</td>
                                        <td class="text-center fw-bold">Dibuat Tanggal</td>
                                        <td class="text-center fw-bold">Permasalahan</td>
                                        <td class="text-center fw-bold">Deskripsi</td>
                                        <td class="text-center fw-bold">Alasan Penolakan</td>
                                        <td class="text-center fw-bold">Gambar</td>
                                        <td class="text-center fw-bold">Status</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ticketing as $ticket)
                                        <tr>
                                            <td class="text-center">{{ $ticket->id }}</td>
                                            <td class="text-center">{{ $ticket->created_at }}</td>
                                            <td class="text-center">{{ $ticket->kategori->nama }}</td>
                                            <td class="text-center">{{ $ticket->deskripsi }}</td>
                                            <td class="text-center">
                                                {{ $ticket->alasan_penolakan ? $ticket->alasan_penolakan : '-' }}
                                            </td>
                                            <td>
                                                <a class="btn btn-danger w-100" href="#" role="button" data-bs-toggle="modal"
                                                    data-bs-target="#buktiModal-{{ $loop->iteration }}">Lihat Bukti</a>
                                            </td>
                                            <td class="text-center">{{ $ticket->status }}</td>
                                        </tr>

                                        <!-- MODAL FOTO BUKTI -->
                                        <div class="modal fade" id="buktiModal-{{ $loop->iteration }}" tabindex="-1"
                                            aria-labelledby="examplebuktiModal-{{ $loop->iteration }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Bukti Foto</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">

                                                        @if ($ticket->bukti_tiket)
                                                            @foreach ($ticket->bukti_tiket as $foto)
                                                                <img class="rounded img-fluid mb-3" src="{{ asset($foto) }}"
                                                                    alt="Bukti Foto Tiket">
                                                            @endforeach
                                                        @else
                                                            <p> Tidak ada Foto Bukti </p>
                                                        @endif

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger"
                                                            data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    @endforeach
                                </tbody>
                            @else
                                Kamu belum ada mengajukan ticketing!
                            @endif
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

</section>

@endsection