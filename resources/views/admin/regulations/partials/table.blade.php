<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Section</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($peraturans as $peraturan)
                <tr>
                    <td>{{ $peraturan->Judul }}</td>
                    <td><a href="{{ route('admin.regulations.edit', $peraturan->id) }}"
                            class="btn btn-primary btn-block">Ubah</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>