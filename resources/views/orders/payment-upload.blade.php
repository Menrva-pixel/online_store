@extends('layouts.app')

@section('title', 'Upload Bukti Pembayaran')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Upload Bukti Pembayaran</h4>
                    <small class="text-muted">No. Pesanan: {{ $order->order_number }}</small>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        Silakan upload bukti pembayaran untuk pesanan ini.
                    </div>
                    
                    <form action="{{ route('my.orders.payment.upload', $order) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="proof_image" class="form-label">Bukti Pembayaran</label>
                            <input type="file" class="form-control @error('proof_image') is-invalid @enderror" 
                                   id="proof_image" name="proof_image" required>
                            @error('proof_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Format: JPG, PNG, GIF (Maks: 2MB)</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Catatan (Opsional)</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload"></i> Upload Bukti Pembayaran
                            </button>
                            <a href="{{ route('my.orders.show', $order) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali ke Detail Pesanan
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection