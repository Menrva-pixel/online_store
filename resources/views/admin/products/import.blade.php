@extends('layouts.app')

@section('title', 'Import Produk - Admin')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-file-import mr-3"></i>Import Produk
        </h1>
        <p class="text-gray-600 mt-2">Import produk dari file Excel/CSV ke sistem.</p>
        
        <!-- Breadcrumb -->
        <nav class="mt-4 flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm text-gray-700 hover:text-blue-600">
                        <i class="fas fa-home mr-2"></i>Dashboard
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                        <a href="{{ route('admin.products.index') }}" class="ml-1 text-sm text-gray-700 hover:text-blue-600 md:ml-2">Produk</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                        <span class="ml-1 text-sm text-gray-500 md:ml-2">Import</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                <div>
                    <p class="text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-red-500 mr-3"></i>
                <div>
                    <p class="text-red-700 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Import Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column - Upload Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-medium text-gray-800">
                        <i class="fas fa-upload mr-2"></i>Upload File
                    </h2>
                </div>
                
                <form action="{{ route('admin.products.import.submit') }}" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf
                    
                    <!-- File Upload -->
                    <div class="mb-6">
                        <label for="file" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-file-excel mr-2"></i>Pilih File
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <label for="file" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>Upload file</span>
                                        <input id="file" name="file" type="file" class="sr-only" accept=".xlsx,.xls,.csv" required>
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">Excel (.xlsx, .xls) atau CSV up to 10MB</p>
                                
                                <!-- File Preview -->
                                <div id="file-preview" class="mt-4 hidden">
                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <div class="flex items-center">
                                            <i class="fas fa-file-excel text-green-500 text-xl mr-3"></i>
                                            <div>
                                                <div id="file-name" class="font-medium text-gray-900"></div>
                                                <div id="file-size" class="text-sm text-gray-500"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @error('file')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Import Options -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-cog mr-2"></i>Opsi Import
                        </label>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       name="skip_header" 
                                       id="skip_header"
                                       value="1"
                                       checked
                                       class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                                <label for="skip_header" class="ml-2 text-sm text-gray-700">
                                    Baris pertama adalah header
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       name="update_existing" 
                                       id="update_existing"
                                       value="1"
                                       class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                                <label for="update_existing" class="ml-2 text-sm text-gray-700">
                                    Update produk yang sudah ada (berdasarkan nama)
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-between pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.products.index') }}" 
                           class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center">
                            <i class="fas fa-upload mr-2"></i>Import Produk
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Column - Instructions -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-medium text-gray-800">
                        <i class="fas fa-info-circle mr-2"></i>Panduan Import
                    </h2>
                </div>
                
                <div class="p-6">
                    <div class="space-y-4">
                        <!-- File Format -->
                        <div>
                            <h3 class="font-medium text-gray-800 mb-2 flex items-center">
                                <i class="fas fa-file mr-2 text-blue-500"></i>Format File
                            </h3>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-2 text-xs"></i>
                                    Excel (.xlsx, .xls)
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-2 text-xs"></i>
                                    CSV (.csv)
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-times text-red-500 mr-2 text-xs"></i>
                                    PDF, Word, dll tidak didukung
                                </li>
                            </ul>
                        </div>

                        <!-- Column Structure -->
                        <div>
                            <h3 class="font-medium text-gray-800 mb-2 flex items-center">
                                <i class="fas fa-columns mr-2 text-purple-500"></i>Struktur Kolom
                            </h3>
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <table class="w-full text-xs">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-2 py-1 text-left">Kolom</th>
                                            <th class="px-2 py-1 text-left">Wajib</th>
                                            <th class="px-2 py-1 text-left">Contoh</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="px-2 py-1 border">name</td>
                                            <td class="px-2 py-1 border text-green-600">Ya</td>
                                            <td class="px-2 py-1 border">Laptop Gaming</td>
                                        </tr>
                                        <tr>
                                            <td class="px-2 py-1 border">description</td>
                                            <td class="px-2 py-1 border text-yellow-600">Tidak</td>
                                            <td class="px-2 py-1 border">Laptop untuk gaming</td>
                                        </tr>
                                        <tr>
                                            <td class="px-2 py-1 border">price</td>
                                            <td class="px-2 py-1 border text-green-600">Ya</td>
                                            <td class="px-2 py-1 border">15000000</td>
                                        </tr>
                                        <tr>
                                            <td class="px-2 py-1 border">stock</td>
                                            <td class="px-2 py-1 border text-green-600">Ya</td>
                                            <td class="px-2 py-1 border">50</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Tips -->
                        <div>
                            <h3 class="font-medium text-gray-800 mb-2 flex items-center">
                                <i class="fas fa-lightbulb mr-2 text-yellow-500"></i>Tips & Saran
                            </h3>
                            <ul class="text-sm text-gray-600 space-y-2">
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mr-2 mt-0.5"></i>
                                    <span>Download template terlebih dahulu untuk memastikan format benar</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mr-2 mt-0.5"></i>
                                    <span>Simpan file dalam format UTF-8 untuk menghindari karakter aneh</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mr-2 mt-0.5"></i>
                                    <span>Import maksimal 1000 baris per file untuk performa optimal</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Download Template -->
                        <div class="pt-4 border-t border-gray-200">
                            <h3 class="font-medium text-gray-800 mb-2 flex items-center">
                                <i class="fas fa-download mr-2 text-green-500"></i>Template
                            </h3>
                            <p class="text-sm text-gray-600 mb-3">
                                Download template untuk memulai import produk.
                            </p>
                            <a href="#" 
                               onclick="downloadTemplate()"
                               class="inline-flex items-center text-green-600 hover:text-green-800 text-sm">
                                <i class="fas fa-file-download mr-2"></i>Download Template Excel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Imports (Optional) -->
    <div class="mt-8">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-medium text-gray-800">
                    <i class="fas fa-history mr-2"></i>Import Terakhir
                </h2>
            </div>
            <div class="p-6">
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-history text-3xl mb-3"></i>
                    <p>Belum ada riwayat import</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
// File Preview
document.getElementById('file').addEventListener('change', function(e) {
    const preview = document.getElementById('file-preview');
    const fileName = document.getElementById('file-name');
    const fileSize = document.getElementById('file-size');
    
    if (this.files && this.files[0]) {
        const file = this.files[0];
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        preview.classList.remove('hidden');
    } else {
        preview.classList.add('hidden');
    }
});

// Format file size
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Download Template
function downloadTemplate() {
    alert('Fitur download template akan diimplementasikan.');
    // Implementasi download template Excel
    // window.location.href = '/admin/products/import/template';
}
</script>
@endsection