@extends('admin.layout.app')

@section('title', 'Dashboard')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Series</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="./">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Manajemen Series</li>
        <li class="breadcrumb-item active" aria-current="page">Edit Series</li>
    </ol>
</div>

@if($errors->has('series'))
    <div class="alert alert-danger mt-2">
        {{ $errors->first('series') }}
    </div>
@endif

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.series.update', $series->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="series">Nama Series</label>
                <input type="text" class="form-control" id="series" name="series" value="{{ $series->series }}">
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.series.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

@endsection