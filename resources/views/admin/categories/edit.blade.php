@extends('layouts.admin')

@section('title', 'Edit Kategori Buku')
@section('page_title', 'Form Edit Kategori')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div>
        <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#800000] hover:underline mb-2">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Kategori
        </a>
        <h1 class="text-2xl font-black text-gray-900">Edit Kategori: {{ $category->name }}</h1>
    </div>

    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-200">
        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Nama Kategori Buku</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" required
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#800000]">
                @error('name')
                    <p class="text-red-600 text-xs mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Deskripsi Singkat (Opsional)</label>
                <textarea name="description" rows="3"
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#800000]">{{ old('description', $category->description) }}</textarea>
                @error('description')
                    <p class="text-red-600 text-xs mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4 flex gap-3">
                <button type="submit" class="px-6 py-3 bg-[#800000] hover:bg-[#600000] text-white text-xs font-bold rounded-xl shadow transition-all flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                </button>
                <a href="{{ route('admin.categories.index') }}" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-xl transition-all">
                    Batal
                </a>
            </div>
        </form>
    </div>

</div>
@endsection
