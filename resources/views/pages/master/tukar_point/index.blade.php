@extends('layouts.app')

@section('title', 'Manajemen Tukar Point')

@section('page-title', 'Manajemen Tukar Point')

@section('content')

<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Request Tukar Point</h2>
</div>

<form method="GET" action="{{ route('tukar-point.index') }}" class="mb-6">
    <div class="flex gap-2">
        <input type="text" name="search"
               class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
               placeholder="Cari berdasarkan nama user..."
               value="{{ request('search') }}">
        <button type="submit"
                class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
            <i class="fas fa-search mr-2"></i>Search
        </button>
    </div>
</form>

<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600">#</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600">User</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600">Produk</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600">Point</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600">Tanggal</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">
                @forelse ($items as $i => $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm">
                            {{ $items->firstItem() + $i }}
                        </td>

                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            {{ $item->user->name ?? '-' }}
                        </td>

                        <td class="px-6 py-4 text-sm">
                            {{ $item->masterPenukaran->produk ?? '-' }}
                        </td>

                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 bg-red-100 text-red-600 rounded-full text-xs font-semibold">
                                {{ abs($item->point) }} poin
                            </span>
                        </td>

                        <td class="px-6 py-4 text-center">
                            @if($item->status == 'pending')
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">
                                    Pending
                                </span>
                            @elseif($item->status == 'approved')
                                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                    Approved
                                </span>
                            @else
                                <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">
                                    Rejected
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-sm">
                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}
                        </td>

                        <td class="px-6 py-4 text-center">
                            @if($item->status == 'pending')
                                <div class="flex justify-center gap-2">

                                    <form action="{{ url('/point/tukar/' . $item->id . '/approve') }}"
                                          method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button class="px-3 py-1.5 bg-green-500 text-white text-xs rounded-lg hover:bg-green-600">
                                            <i class="fas fa-check mr-1"></i>Approve
                                        </button>
                                    </form>

                                    <form action="{{ url('/point/tukar/' . $item->id . '/reject') }}"
                                          method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button class="px-3 py-1.5 bg-red-500 text-white text-xs rounded-lg hover:bg-red-600">
                                            <i class="fas fa-times mr-1"></i>Reject
                                        </button>
                                    </form>

                                </div>
                            @else
                                <span class="text-gray-400 text-xs">Sudah diproses</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-12 text-gray-400">
                            Tidak ada request tukar point
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-6 py-4 border-t border-gray-200">
        {{ $items->links() }}
    </div>
</div>

@endsection