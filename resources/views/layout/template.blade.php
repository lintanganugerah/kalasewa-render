<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalasewa - Rental Cosplay</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />


    <!-- AWESOME CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Datepicker -->
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.2/css/dataTables.bootstrap5.css">

    <!-- CUSTOM CSS -->
    <link rel="stylesheet" href="font.css">

</head>

<body>


    @yield('content')

    <!-- BOOTSTRAP SCRIPT -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css"
        integrity="sha512-OQDNdI5rpnZ0BRhhJc+btbbtnxaj+LdQFeh0V9/igiEPDiWE2fG+ZsXl0JEH+bjXKPJ3zcXqNyP4/F/NegVdZg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.8/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/elevatezoom/3.0.8/jquery.elevatezoom.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
    <script src="//cdn.datatables.net/2.1.2/js/dataTables.js"></script>
    <script src="//cdn.datatables.net/2.1.2/js/dataTables.bootstrap5.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2 for series with placeholder
            $('#selectSeries').select2({
                theme: "bootstrap-5",
                width: '100%', // Ensure width is properly set
                placeholder: "Series", // Set placeholder for series
                allowClear: true // Allow clearing the selection
            });

            // Initialize Select2 for size with placeholder
            $('#selectBrand').select2({
                theme: "bootstrap-5",
                width: '100%', // Ensure width is properly set
                placeholder: "Brand", // Set placeholder for size
                allowClear: true // Allow clearing the selection
            });
            // Initialize Select2 for size with placeholder
            $('#selectGender').select2({
                theme: "bootstrap-5",
                width: '100%', // Ensure width is properly set
                placeholder: "Gender", // Set placeholder for size
                allowClear: true // Allow clearing the selection
            });
            // Initialize Select2 for size with placeholder
            $('#selectSize').select2({
                theme: "bootstrap-5",
                width: '100%', // Ensure width is properly set
                placeholder: "Size", // Set placeholder for size
                allowClear: true // Allow clearing the selection
            });
            $('#selectGrade').select2({
                theme: "bootstrap-5",
                width: '100%', // Ensure width is properly set
                placeholder: "Grade", // Set placeholder for size
                allowClear: true // Allow clearing the selection
            });
            $('#selectRekening').select2({
                theme: "bootstrap-5",
                width: '100%', // Ensure width is properly set
                placeholder: "Rekening/E-Wallet", // Set placeholder for size
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('profileImage').addEventListener('click', function() {
                var dropdown = new bootstrap.Dropdown(document.getElementById('profileDropdown'));
                dropdown.toggle();
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.carousel-item-detail img').elevateZoom({
                zoomType: "lens",
                lensShape: "round",
                lensSize: 250
            });
        });
    </script>



    <script>
        new DataTable('#table-history', {
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, 'All']
            ],
            search: {
                return: true
            }
        });
    </script>

    <script>
        new DataTable('#review-table', {
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, 'All']
            ],
            search: {
                return: true
            }
        });
    </script>

    <script>
        new DataTable('#table-penarikan', {
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, 'All']
            ],
            search: {
                return: true
            }
        });
    </script>

    <script>
        new DataTable('#table-ticketing', {
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, 'All']
            ],
            search: {
                return: true
            }
        });
    </script>

    <script type="importmap">
                {
                    "imports": {
                        "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/42.0.2/ckeditor5.js",
                        "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/42.0.2/"
                    }
                }
            </script>

    <script type="module">
        import {
            ClassicEditor,
            Essentials,
            Bold,
            Italic,
            Font,
            Paragraph
        } from 'ckeditor5';

        ClassicEditor
            .create(document.querySelector('#editor'), {
                plugins: [Essentials, Bold, Italic, Font, Paragraph],
                toolbar: {
                    items: [
                        'undo', 'redo', '|', 'bold', 'italic', '|',
                        'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor'
                    ]
                }
            })
            .then( /* ... */ )
            .catch( /* ... */ );
    </script>

    @yield('scripts')

</body>

</html>
