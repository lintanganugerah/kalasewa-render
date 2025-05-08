@if ($topseries->isNotEmpty())
    <table class="table w-100 table-borderless table-responsive">
        <thead>
            <tr>
                <th>Rank</th>
                <th>Nama Series</th>
                <th width="4%">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($topseries as $idseries => $series)
                <tr>
                    <td class="align-middle">
                        #{{ $loop->iteration }}
                    </td>
                    <td class="align-middle">
                        <div>
                            <h5 class="cut-text">{{ $series->first()->series->series }}
                            </h5>
                        </div>
                    </td>
                    <td class="align-middle">
                        <p class="m-0 small fw-bold card-link" href="#">
                            {{ $series->first()->banyak_dipesan }}x disewa</p>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p> Belum ada top series</p>
@endif
