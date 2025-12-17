@extends('layouts.app')

@section('title', 'Upload Bukti Pembayaran - Toko Online')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <!-- Progress Steps -->
    <div class="mb-12">
        <div class="flex items-center justify-center">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="ml-4">
                    <span class="block text-sm font-medium text-blue-600">Keranjang</span>
                </div>
            </div>
            
            <div class="flex-1 border-t-2 border-blue-600 mx-4"></div>
            
            <div class="flex items-center">
                <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white">
                    <span class="font-bold">2</span>
                </div>
                <div class="ml-4">
                    <span class="block text-sm font-medium text-blue-600">Checkout</span>
                </div>
            </div>
            
            <div class="flex-1 border-t-2 border-blue-600 mx-4"></div>
            
            <div class="flex items-center">
                <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white">
                    <span class="font-bold">3</span>
                </div>
                <div class="ml-4">
                    <span class="block text-sm font-medium text-blue-600">Pembayaran</span>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Order Info Header -->
        <div class="bg-blue-50 p-6 border-b">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">
                        <i class="fas fa-credit-card mr-3"></i>Upload Bukti Pembayaran
                    </h1>
                    <p class="text-gray-600 mt-2">
                        Order: <span class="font-bold text-blue-600">{{ $order->order_number }}</span>
                        | Total: <span class="font-bold text-green-600">{{ $order->formatted_total }}</span>
                    </p>
                </div>
                
                <div class="text-right">
                    <div class="text-sm text-gray-500">Batas Waktu:</div>
                    <div class="text-lg font-bold text-red-600">
                        {{ $order->payment_due_at->format('d M Y H:i') }}
                    </div>
                </div>
            </div>
        </div>

        <div class="p-6">
            <!-- Payment Instructions -->
            <div class="mb-8 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <h3 class="font-bold text-yellow-800 mb-2">
                    <i class="fas fa-info-circle mr-2"></i>Instruksi Pembayaran:
                </h3>
                <ol class="list-decimal list-inside space-y-2 text-yellow-700">
                    <li>Transfer ke rekening yang tersedia</li>
                    <li>Screenshot atau foto bukti transfer</li>
                    <li>Upload bukti transfer di form ini</li>
                    <li>Tunggu verifikasi dari Customer Service</li>
                </ol>
            </div>

            <form action="{{ route('payment.upload', $order) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="space-y-6">
                    <!-- Payment Method -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-money-check-alt mr-2"></i>Metode Pembayaran
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <label class="payment-method-option">
                                <input type="radio" name="payment_method" value="bank_transfer" class="hidden" checked>
                                <div class="border-2 border-gray-300 rounded-lg p-4 text-center cursor-pointer hover:border-blue-500 payment-method-card">
                                    <i class="fas fa-university text-3xl text-gray-600 mb-2"></i>
                                    <div class="font-medium">Transfer Bank</div>
                                </div>
                            </label>
                            
                            <label class="payment-method-option">
                                <input type="radio" name="payment_method" value="e_wallet" class="hidden">
                                <div class="border-2 border-gray-300 rounded-lg p-4 text-center cursor-pointer hover:border-blue-500 payment-method-card">
                                    <i class="fas fa-mobile-alt text-3xl text-gray-600 mb-2"></i>
                                    <div class="font-medium">E-Wallet</div>
                                </div>
                            </label>
                            
                            <label class="payment-method-option">
                                <input type="radio" name="payment_method" value="cod" class="hidden">
                                <div class="border-2 border-gray-300 rounded-lg p-4 text-center cursor-pointer hover:border-blue-500 payment-method-card">
                                    <i class="fas fa-money-bill-wave text-3xl text-gray-600 mb-2"></i>
                                    <div class="font-medium">COD</div>
                                </div>
                            </label>
                        </div>
                        @error('payment_method')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bank Details (Show only for bank transfer) -->
                    <div id="bank-details" class="space-y-4">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h4 class="font-bold text-blue-800 mb-3">Rekening Tujuan:</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <div class="text-sm text-gray-600">Bank</div>
                                    <div class="font-medium">Bank Central Asia (BCA)</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-600">Nomor Rekening</div>
                                    <div class="font-medium">1234567890</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-600">Atas Nama</div>
                                    <div class="font-medium">Toko Online Indonesia</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-600">Jumlah Transfer</div>
                                    <div class="font-bold text-green-600">{{ $order->formatted_total }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Bank Name -->
                        <div>
                            <label for="bank_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Bank Pengirim
                            </label>
                            <input type="text" 
                                   id="bank_name" 
                                   name="bank_name"
                                   value="{{ old('bank_name') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Contoh: BCA, Mandiri, BNI">
                            @error('bank_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Account Number -->
                        <div>
                            <label for="account_number" class="block text-sm font-medium text-gray-700 mb-2">
                                Nomor Rekening Pengirim
                            </label>
                            <input type="text" 
                                   id="account_number" 
                                   name="account_number"
                                   value="{{ old('account_number') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Contoh: 1234567890">
                            @error('account_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Payment Proof Upload -->
                    <div>
                        <label for="proof_image" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-camera mr-2"></i>Bukti Pembayaran
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-500 transition duration-300"
                             id="dropzone">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                            <p class="text-gray-600 mb-2">Drag & drop file atau klik untuk upload</p>
                            <p class="text-sm text-gray-500 mb-4">Format: JPG, PNG (Max: 5MB)</p>
                            <input type="file" 
                                   id="proof_image" 
                                   name="proof_image"
                                   accept="image/jpeg,image/png"
                                   class="hidden"
                                   required>
                            <button type="button" 
                                    onclick="document.getElementById('proof_image').click()"
                                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                                <i class="fas fa-upload mr-2"></i>Pilih File
                            </button>
                        </div>
                        <div id="preview" class="mt-4 hidden">
                            <img id="preview-image" class="max-w-xs rounded-lg shadow">
                        </div>
                        @error('proof_image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-sticky-note mr-2"></i>Catatan (Opsional)
                        </label>
                        <textarea id="notes" 
                                  name="notes"
                                  rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Tambahkan catatan jika diperlukan">{{ old('notes') }}</textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-between pt-6 border-t">
                        <a href="{{ route('checkout') }}" 
                           class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali
                        </a>
                        
                        <button type="submit" 
                                class="bg-green-600 text-white px-8 py-3 rounded-lg hover:bg-green-700">
                            <i class="fas fa-paper-plane mr-2"></i>Kirim Bukti Pembayaran
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Important Notes -->
    <div class="mt-8 bg-red-50 border border-red-200 rounded-lg p-6">
        <h3 class="font-bold text-red-800 mb-3">
            <i class="fas fa-exclamation-triangle mr-2"></i>Penting!
        </h3>
        <ul class="list-disc list-inside space-y-2 text-red-700">
            <li>Pastikan nominal transfer sesuai dengan total pesanan</li>
            <li>Bukti transfer harus jelas terbaca</li>
            <li>Verifikasi membutuhkan waktu 1-2 jam kerja</li>
            <li>Pesanan akan dibatalkan otomatis setelah 24 jam tanpa pembayaran</li>
            <li>Hubungi CS jika ada kendala</li>
        </ul>
    </div>
</div>

@push('styles')
<style>
.payment-method-card {
    transition: all 0.3s ease;
}
.payment-method-option input:checked + .payment-method-card {
    border-color: #3b82f6;
    background-color: #eff6ff;
}
</style>
@endpush

@push('scripts')
<script>
// Payment method selection
document.querySelectorAll('.payment-method-option input').forEach(radio => {
    radio.addEventListener('change', function() {
        const bankDetails = document.getElementById('bank-details');
        if (this.value === 'bank_transfer') {
            bankDetails.classList.remove('hidden');
            bankDetails.querySelectorAll('input').forEach(input => {
                input.required = true;
            });
        } else {
            bankDetails.classList.add('hidden');
            bankDetails.querySelectorAll('input').forEach(input => {
                input.required = false;
            });
        }
    });
});

// File upload preview
const proofImage = document.getElementById('proof_image');
const preview = document.getElementById('preview');
const previewImage = document.getElementById('preview-image');
const dropzone = document.getElementById('dropzone');

proofImage.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            preview.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    }
});

// Drag and drop
dropzone.addEventListener('dragover', function(e) {
    e.preventDefault();
    this.classList.add('border-blue-500', 'bg-blue-50');
});

dropzone.addEventListener('dragleave', function(e) {
    e.preventDefault();
    this.classList.remove('border-blue-500', 'bg-blue-50');
});

dropzone.addEventListener('drop', function(e) {
    e.preventDefault();
    this.classList.remove('border-blue-500', 'bg-blue-50');
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        proofImage.files = files;
        
        // Trigger change event
        const event = new Event('change', { bubbles: true });
        proofImage.dispatchEvent(event);
    }
});

// Auto-select bank transfer on load
document.querySelector('input[value="bank_transfer"]').checked = true;
document.querySelector('input[value="bank_transfer"]').dispatchEvent(new Event('change'));
</script>
@endpush
@endsection