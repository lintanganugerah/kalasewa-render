@extends('layout.selllayout')
@section('content')

<body>
    <div class="container mt-5">
        <div class="form-floating mb-3">
            <select class="form-select" id="selectSeries" name="series" aria-label="Floating label select example"
                required>
                <option value="Genshin">Genshin Impact</option>
                <option value="Blue Archive">Blue Archive</option>
                <option value="Honkai Star Rail">Honkai Star Rail</option>
                <option value="Wuthering Waves">Wuthering Waves</option>
                <option value="Attack on Titan">Attack on Titan</option>
            </select>
            <label for="selectSeries">Series</label>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Bootstrap JS (Optional, if you need Bootstrap JS features) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#selectSeries').select2({
                theme: 'bootstrap-5'
            });
        });
    </script>
</body>
@endsection