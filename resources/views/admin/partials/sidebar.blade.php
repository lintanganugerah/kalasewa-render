<ul class="navbar-nav sidebar sidebar-light accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin.dashboard') }}">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('admincss/img/logo/Logo_Kalasewa.png') }}">
        </div>

        <div class="sidebar-brand-text mx-3">Kalasewa</div>
    </a>

    <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('admin.dashboard') }}" data-toggle="collapse"
            data-target="#collapseTable" aria-expanded="false" aria-controls="collapseTable" id="dashboard">
            <i class="fas fa-fw fa-atom"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('admin.series.index') }}" data-toggle="collapse"
            data-target="#collapseTable" aria-expanded="false" aria-controls="collapseTable" id="manajemenSeries">
            <i class="fas fa-fw fa-table"></i>
            <span>Manajemen Series</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('admin.users.index') }}" data-toggle="collapse"
            data-target="#collapseTable" aria-expanded="false" aria-controls="collapseTable" id="manajemenPengguna">
            <i class="fas fa-fw fa-user"></i>
            <span>Manajemen User</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('admin.verify.index') }}" data-toggle="collapse"
            data-target="#collapseTable" aria-expanded="false" aria-controls="collapseTable" id="verifikasiUser">
            <i class="fas fa-fw fa-user-check"></i>
            <span>Verifikasi User</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('admin.banks.index') }}" data-toggle="collapse"
            data-target="#collapseTable" aria-expanded="false" aria-controls="collapseTable" id="rekeningTujuan">
            <i class="fas fa-fw fa-landmark"></i>
            <span>Kelola Daftar Bank</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('admin.refunds.index') }}" data-toggle="collapse"
            data-target="#collapseTable" aria-expanded="false" aria-controls="collapseTable" id="refund">
            <i class="fas fa-fw fa-money-bill-wave"></i>
            <span>Manajemen Dana</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('admin.ticket.index') }}" data-toggle="collapse"
            data-target="#collapseTable" aria-expanded="false" aria-controls="collapseTable" id="ticket">
            <i class="fas fa-fw fa-ticket-alt"></i>
            <span>Manajemen Ticket</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('admin.retur.index') }}" data-toggle="collapse"
            data-target="#collapseTable" aria-expanded="false" aria-controls="collapseTable" id="retur">
            <i class="fas fa-fw fa-boxes"></i>
            <span>Pengajuan Retur</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('admin.penalty.index') }}" data-toggle="collapse"
            data-target="#collapseTable" aria-expanded="false" aria-controls="collapseTable" id="penalty">
            <i class="fas fa-fw fa-money-check"></i>
            <span>Pengajuan Denda</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('admin.verify.index') }}" data-toggle="collapse"
            data-target="#collapseTable" aria-expanded="false" aria-controls="collapseTable" id="peraturanPlatform">
            <i class="fas fa-fw fa-book"></i>
            <span>Peraturan Platform</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('admin.aboutus.index') }}" data-toggle="collapse"
            data-target="#collapseTable" aria-expanded="false" aria-controls="collapseTable" id="tentangKalasewa">
            <i class="fas fa-fw fa-book-reader"></i>
            <span>Tentang Kalasewa</span>
        </a>
    </li>

</ul>

<script>
    document.getElementById('dashboard').addEventListener('click', function (event) {
        event.preventDefault();

        window.location.href = '{{ route('admin.dashboard') }}';
    });

    document.getElementById('manajemenSeries').addEventListener('click', function (event) {
        event.preventDefault();

        window.location.href = '{{ route('admin.series.index') }}';
    });

    document.getElementById('manajemenPengguna').addEventListener('click', function (event) {
        event.preventDefault();

        window.location.href = '{{ route('admin.users.index') }}';
    });

    document.getElementById('verifikasiUser').addEventListener('click', function (event) {
        event.preventDefault();
        window.location.href = '{{ route('admin.verify.index') }}';
    });

    document.getElementById('rekeningTujuan').addEventListener('click', function (event) {
        event.preventDefault();
        window.location.href = '{{ route('admin.banks.index') }}';
    });

    document.getElementById('refund').addEventListener('click', function (event) {
        event.preventDefault();
        window.location.href = '{{ route('admin.refunds.index') }}';
    });

    document.getElementById('ticket').addEventListener('click', function (event) {
        event.preventDefault();
        window.location.href = '{{ route('admin.ticket.index') }}';
    });

    document.getElementById('retur').addEventListener('click', function (event) {
        event.preventDefault();
        window.location.href = '{{ route('admin.retur.index') }}';
    });

    document.getElementById('penalty').addEventListener('click', function (event) {
        event.preventDefault();
        window.location.href = '{{ route('admin.penalty.index') }}';
    });

    document.getElementById('peraturanPlatform').addEventListener('click', function (event) {
        event.preventDefault();
        window.location.href = '{{ route('admin.regulations.index') }}';
    });

    document.getElementById('tentangKalasewa').addEventListener('click', function (event) {
        event.preventDefault();
        window.location.href = '{{ route('admin.aboutus.index') }}';
    });
</script>