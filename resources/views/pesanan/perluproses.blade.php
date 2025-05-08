@extends('layout.selllayout')
@section('content')

<div class="row">
  <div class="col">
    <div class="text-left mb-5 mt-3">
      <h1 class="fw-medium text-dark">PESANAN</h1>
      <h5 class="text-dark">Perlu di Proses</h5>
    </div>

    <div class="row gx-5">

      <!-- Earnings (Monthly) Card Example -->
      <div class="col mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                  Permintaan Pinjam</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">aa</div>
              </div>
              <div class="col-auto test" id="icon-beranda-1">
                <i class="fas fa-file-export fa-2x text-gray-400"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Earnings (Monthly) Card Example -->
      <div class="col mb-4">
        <div class="card border-left-info shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                  Permintaan Pengembalian</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">aa</div>
              </div>
              <div class="col-auto" id="icon-beranda-2">
                <i class="fas fa-file-import fa-2x text-gray-400"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Earnings (Monthly) Card Example -->
      <div class="col mb-4">
        <div class="card border-left-success shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Berkas (Tersedia)
                </div>
                <div class="row no-gutters align-items-center">
                  <div class="col-auto">
                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">aa</div>
                  </div>
                </div>
              </div>
              <div class="col-auto" id="icon-beranda-3">
                <i class="fas fa-file-circle-check fa-2x text-gray-400"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Pending Requests Card Example -->
      <div class="col mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                  Total Berkas (Dipinjam)</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">aa</div>
              </div>
              <div class="col-auto" id="icon-beranda-4">
                <i class="fas fa-file-circle-exclamation fa-2x text-gray-400"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="text-white p-4 rounded-3 mb-4 mt-4" style="background-color: #0D3148;">
      <h4 id="judul-tabel-1" class="mb-3">Permintaan Pinjam</h4>
      <table id="tabel" class="no-more-tables table text-dark table-sm table-light w-100 tabel-data"
        style="word-wrap: break-word;" cellspacing="0">
        <thead>
          <tr>
            <th>#</th>
            <th>No Rek</th>
            <th>Nama</th>
            <th>CIF</th>
            <th>Lokasi Berkas</th>
            <th>Jenis</th>
            <th>User</th>
            <th>Lemari</th>
            <th>Rak</th>
            <th>Baris</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>

    <script>
      $(document).ready(function () {
        const date = new Date();
        $('.tabel-data').DataTable({
          lengthMenu: [
            [3, 5, 10, 25, -1],
            [3, 5, 10, 25, 'All']
          ],
          // fixedHeader: true,
          // order: [
          //   [6, 'asc']
          // ],
          // rowGroup: {
          //   dataSrc: 6
          // }
        });
        $('#downloadable').DataTable({
          lengthMenu: [
            [3, 5, 10, 25, -1],
            [3, 5, 10, 25, 'All']
          ],
          dom: 'Bfrtip',
          buttons: [{
            extend: 'excel',
            text: 'Excel',
            title: "Berkas sedang dipinjam",
            messageTop: date,
            messageBottom: "Jika berkas sudah tidak digunakan lagi. Mohon kembalikan berkas melalui menu 'Pengembalian'",
            filename: "Berkas sedang dipinjam"
          },
          {
            extend: 'pdf',
            text: 'PDF',
            title: "Berkas sedang dipinjam",
            messageTop: date,
            messageBottom: "Jika berkas sudah tidak digunakan lagi. Mohon mengembalikan berkas melalui menu 'Pengembalian'",
            filename: "Berkas sedang dipinjam"
          },
          {
            extend: 'print',
            text: 'Print',
            title: "Berkas sedang dipinjam",
            messageTop: date,
            messageBottom: "Jika berkas sudah tidak digunakan lagi. Mohon mengembalikan berkas melalui menu 'Pengembalian'",
            filename: "Berkas sedang dipinjam"
          }, 'pageLength',
          ]
        });
      });
    </script>
    @endsection