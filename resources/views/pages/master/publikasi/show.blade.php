@extends('layouts.app')

@section('content')

    <h4>{{ $data->title }}</h4>
    <p>Pembuat: {{ $data->creator }}</p>
    <p>{!! nl2br(e($data->description)) !!}</p>

    <h5>Foto</h5>
    <div class="row">
        @foreach ($data->photos as $photo)
            <div class="col-md-2 mb-3">
                <img src="{{ asset('storage/' . $photo->file_path) }}" class="img-thumbnail">
            </div>
        @endforeach
    </div>

    <h5>Video</h5>
    <ul>
        @foreach ($data->videos as $v)
            <li><a href="{{ $v->link }}" target="_blank">{{ $v->link }}</a></li>
        @endforeach
    </ul>

    <a href="{{ route('publikasi.index') }}" class="btn btn-secondary mt-3">Kembali</a>

@endsection