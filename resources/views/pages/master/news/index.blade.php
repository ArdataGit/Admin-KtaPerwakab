@extends('layouts.app')

@section('title', 'Daftar Artikel')

@section('page-title', 'Daftar Artikel')

@section('content')

    <!-- Page Header -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Daftar Artikel</h2>
        <a href="{{ route('news.create') }}"
           class="px-5 py-2.5 bg-gradient-to-r from-[#3E9A3E] to-[#85C955] text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all">
            <i class="fas fa-plus mr-2"></i>Buat Artikel
        </a>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">#</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Judul</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Author</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Cover</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($articles as $i => $a)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $articles->firstItem() + $i }}</td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('news.show', $a->id) }}" 
                                   class="font-medium text-[#3E9A3E] hover:text-[#2d7a2d] hover:underline">
                                    {{ $a->title }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium capitalize">
                                    {{ $a->category }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $a->author->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $a->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($a->cover_url)
                                    <a href="{{ $a->cover_url }}" target="_blank" class="inline-block">
                                        <img src="{{ $a->cover_url }}"
                                             class="w-16 h-16 object-cover rounded-lg shadow-md hover:shadow-lg transition-shadow mx-auto"
                                             alt="Cover">
                                    </a>
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('news.edit', $a->id) }}"
                                       class="px-3 py-1.5 bg-yellow-500 text-white text-xs font-medium rounded-lg hover:bg-yellow-600 transition-colors">
                                        <i class="fas fa-edit mr-1"></i>Edit
                                    </a>

                                    <form action="{{ route('news.destroy', $a->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                onclick="return confirm('Hapus artikel ini?')"
                                                class="px-3 py-1.5 bg-red-500 text-white text-xs font-medium rounded-lg hover:bg-red-600 transition-colors">
                                            <i class="fas fa-trash mr-1"></i>Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-newspaper text-4xl mb-2 text-gray-300"></i>
                                <p>Belum ada artikel</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $articles->links() }}
        </div>
    </div>

@endsection