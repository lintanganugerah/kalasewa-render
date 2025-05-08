<link rel="stylesheet" type="text/css" href="{{ asset('css/searchbar.css') }}" />

<div class="row">
    <div class="searchbar">
        <div class="input-group">
            <form action="{{ route('search') }}" method="GET" class="d-flex w-100">
                @csrf
                <input type="text" name="search" class="form-control form-search custom-search-bar"
                    placeholder="Mau cosplay apa hari ini?" aria-label="Search" />
                <button class="btn py-2 custom-search-button" type="submit" id="search-button">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>
</div>