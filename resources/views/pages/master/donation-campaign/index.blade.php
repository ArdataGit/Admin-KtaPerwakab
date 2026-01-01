@extends('layouts.app')
@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h4>Daftar Campaign Donasi</h4>
        <button class="btn btn-primary" data-toggle="modal" data-target="#modalCreateCampaign">
            Tambah Campaign
        </button>
    </div>
    <div class="card shadow mb-4">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th>Judul Campaign</th>
                        <th>Periode</th>
                        <th>Status</th>
                        <th width="10%">Thumbnail</th>
                        <th width="18%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $i => $item)
                        <tr>
                            <td>{{ $items->firstItem() + $i }}</td>
                            <td>
                                <strong>{{ $item->title }}</strong><br>
                                <small class="text-muted">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($item->description), 80) }}
                                </small>
                            </td>
                            <td>
                                {{ $item->start_date->format('d M Y') }}
                                @if ($item->end_date)
                                    - {{ $item->end_date->format('d M Y') }}
                                @endif
                            </td>
                            <td>
                                @if ($item->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                @if ($item->thumbnail)
                                    <img src="{{ asset('storage/' . $item->thumbnail) }}" width="60" class="rounded shadow-sm">
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning" data-toggle="modal"
                                    data-target="#modalEditCampaign{{ $item->id }}">
                                    Edit
                                </button>
                                <form action="{{ route('master.donation-campaign.destroy', $item->id) }}" method="POST"
                                    style="display:inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Hapus campaign ini?')" class="btn btn-sm btn-danger">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                Data campaign belum tersedia
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $items->links('pagination::bootstrap-4') }}
        </div>
    </div>
    {{-- MODAL EDIT --}}
    @foreach ($items as $item)
        <div class="modal fade" id="modalEditCampaign{{ $item->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <form action="{{ route('master.donation-campaign.update', $item->id) }}" method="POST"
                    enctype="multipart/form-data" class="modal-content campaign-form">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Campaign Donasi</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        @include('pages.master.donation-campaign.form', ['data' => $item])
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning">Update</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
    {{-- MODAL CREATE --}}
    <div class="modal fade" id="modalCreateCampaign" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('master.donation-campaign.store') }}" method="POST" enctype="multipart/form-data"
                class="modal-content campaign-form">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Campaign Donasi</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    @include('pages.master.donation-campaign.form', ['data' => null])
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
