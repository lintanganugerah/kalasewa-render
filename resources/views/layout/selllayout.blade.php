<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kalasewa Seller</title>

    <link rel="icon" href="{{ asset('img/kalasewa_logo.png') }}" />

    <!-- Custom fonts for this template-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.0/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <link
        href="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.5/af-2.6.0/b-2.4.1/b-colvis-2.4.1/b-html5-2.4.1/b-print-2.4.1/cr-1.7.0/date-1.5.1/fc-4.3.0/fh-3.4.0/kt-2.10.0/r-2.5.0/rg-1.4.0/rr-1.4.1/sc-2.2.0/sb-1.5.0/sp-2.2.0/sl-1.7.0/sr-1.3.0/datatables.min.css"
        rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script
        src="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.5/af-2.6.0/b-2.4.1/b-colvis-2.4.1/b-html5-2.4.1/b-print-2.4.1/cr-1.7.0/date-1.5.1/fc-4.3.0/fh-3.4.0/kt-2.10.0/r-2.5.0/rg-1.4.0/rr-1.4.1/sc-2.2.0/sb-1.5.0/sp-2.2.0/sl-1.7.0/sr-1.3.0/datatables.min.js">
    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link type="text/css" href="{{ asset('seller/seller.css') }}" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@latest/dist/chartjs-adapter-date-fns.min.js">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment@latest/dist/chartjs-adapter-moment.min.js"></script>

    <style>
        .btn-kalasewa {
            background-color: #EE1B2F;
            color: white;
        }

        .btn-kalasewa-dark {
            background-color: #aa1927;
            color: white;
        }

        .btn-kalasewa:hover {
            background-color: #CE2525;
            color: white;
        }

        .kalasewa-color {
            color: #CE2525 !important;
        }

        .kalasewa-color-dark {
            color: #aa1927 !important;
        }

        .btn-outline:hover {
            background-color: #CE2525;
            color: white !important;
        }

        .btn-outline {
            border-color: #EE1B2F;
            color: black;
        }

        #topbar {
            display: none;
        }

        /* menyembunyikan topbar jika lebar layar >= 768px */
        @media (max-width: 768px) {
            #topbar {
                display: block;
            }
        }
    </style>
</head>

<body id="page-top">
    <nav class="navbar navbar-expand topbar shadow-lg kalasewa-color-topbar" id="topbar">

        <!-- Sidebar Toggle (Topbar) -->
        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3" id="sidebar-toggle-btn">
            <i class="fa-solid fa-bars" style="color: #AA1927;"></i>
        </button>
    </nav>
    <!-- Page Wrapper -->
    <div id="wrapper">

        @include('layout.sellsidebar')

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column mx-3 pt-3">

            <!-- Main Content -->

            @yield('content')

            <!-- End of Content Wrapper -->

        </div>
        <!-- End of Page Wrapper -->

        <!-- Bootstrap core JavaScript-->
        <!--<script src="vendor/jquery/jquery.min.js"></script>-->
        <!--<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>-->

        <!-- Core plugin JavaScript-->
        <!--<script src="vendor/jquery-easing/jquery.easing.min.js"></script>-->

        <!-- Custom scripts for all pages-->
        <!--<script src="js/sb-admin-2.min.js"></script>-->

        <!-- Page level plugins -->
        <!--<script src="vendor/chart.js/Chart.min.js"></script>-->

        <!-- Page level custom scripts -->
        <!--<script src="js/demo/chart-area-demo.js"></script>-->
        <!--<script src="js/demo/chart-pie-demo.js"></script>-->
</body>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
    integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"
    integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous">
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="{{ asset('Style/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<script>
    //     function preserveToggledOnScroll() {
    //         var width = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
    //         var sidebar = document.querySelector('.sidebar');
    //         var body = document.getElementById('page-top');
    //         console.log('yes 1');
    //         if (width < 768) {
    //             window.addEventListener('scroll', function() {
    //                 if (!sidebar.classList.contains('toggled')) {
    //                     sidebar.classList.remove('toggled');
    //                     body.classList.remove('sidebar-toggled');
    //                 }
    //             });
    //             console.log('yes');
    //         }
    //     }
    //     window.onload = function() {
    //         preserveToggledOnScroll();
    //     };
    // 
</script>

<script src="{{ asset('Style/js/sb-admin-2.min.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script
    src="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.5/af-2.6.0/b-2.4.1/b-colvis-2.4.1/b-html5-2.4.1/b-print-2.4.1/cr-1.7.0/date-1.5.1/fc-4.3.0/fh-3.4.0/kt-2.10.0/r-2.5.0/rg-1.4.0/rr-1.4.1/sc-2.2.0/sb-1.5.0/sp-2.2.0/sl-1.7.0/sr-1.3.0/datatables.min.js">
</script>

</html>
