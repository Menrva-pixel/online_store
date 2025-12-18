<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipping Label - {{ $order->order_number }}</title>
    <style>
        @page { margin: 0; size: 100mm 150mm; }
        body { font-family: Arial, sans-serif; margin: 0; padding: 10px; font-size: 11px; }
        .label-container { width: 90mm; height: 140mm; margin: 0 auto; border: 1px solid #ccc; padding: 8px; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 5px; margin-bottom: 10px; }
        .company-name { font-size: 14px; font-weight: bold; }
        .company-address { font-size: 8px; }
        .title { text-align: center; font-size: 12px; font-weight: bold; margin: 10px 0; text-decoration: underline; }
        .section { margin-bottom: 8px; }
        .section-title { font-weight: bold; font-size: 10px; background-color: #f0f0f0; padding: 2px 5px; margin-bottom: 3px; }
        .info-grid { display: grid; grid-template-columns: 1fr; gap: 2px; }
        .info-item { margin-bottom: 3px; }
        .info-label { font-weight: bold; font-size: 9px; }
        .info-value { font-size: 10px; }
        .barcode { text-align: center; margin: 15px 0; }
        .barcode-text { font-family: 'Libre Barcode 128', cursive; font-size: 28px; letter-spacing: 2px; }
        .tracking-number { text-align: center; font-family: monospace; font-size: 12px; font-weight: bold; margin-top: 5px; }
        .from-to { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin: 10px 0; }
        .address-box { border: 1px solid #333; padding: 5px; }
        .address-title { font-weight: bold; text-align: center; background-color: #f0f0f0; margin: -5px -5px 5px -5px; padding: 3px; }
        .warning { text-align: center; font-size: 9px; color: red; font-weight: bold; margin: 10px 0; border: 1px solid red; padding: 3px; }
        .package-info { display: grid; grid-template-columns: repeat(3, 1fr); gap: 5px; margin: 10px 0; }
        .package-item { text-align: center; border: 1px solid #ccc; padding: 3px; }
        .package-label { font-size: 8px; }
        .package-value { font-weight: bold; font-size: 10px; }
        .footer { margin-top: 10px; text-align: center; font-size: 8px; color: #666; }
        .stamp-area { height: 40px; border: 1px dashed #ccc; margin-top: 10px; text-align: center; line-height: 40px; font-size: 9px; color: #999; }
        /* Print specific styles */
        @media print {
            body { -webkit-print-color-adjust: exact; }
            .label-container { border: none; }
            .page-break { page-break-after: always; }
        }
    </style>
</head>
<body>
    <!-- Label 1: Shipping Information -->
    <div class="label-container">
        <div class="header">
            <div class="company-name">TOKO ONLINE</div>
            <div class="company-address">Jl. Contoh No. 123, Kota Contoh</div>
        </div>
        
        <div class="title">LABEL PENGIRIMAN</div>
        
        <!-- Barcode -->
        <div class="barcode">
            <div class="barcode-text">*{{ $order->order_number }}*</div>
            <div class="tracking-number">{{ $order->tracking_number ?? 'TBA' }}</div>
        </div>
        
        <!-- From / To -->
        <div class="from-to">
            <div class="address-box">
                <div class="address-title">DARI</div>
                <div class="info-value">TOKO ONLINE</div>
                <div class="info-value">Jl. Contoh No. 123</div>
                <div class="info-value">Kota Contoh 12345</div>
                <div class="info-value">Telp: (021) 12345678</div>
            </div>
            
            <div class="address-box">
                <div class="address-title">KEPADA</div>
                <div class="info-value"><strong>{{ $order->recipient_name }}</strong></div>
                <div class="info-value">{{ $order->shipping_address }}</div>
                <div class="info-value">Telp: {{ $order->recipient_phone ?? '-' }}</div>
            </div>
        </div>
        
        <!-- Shipping Info -->
        <div class="section">
            <div class="section-title">INFORMASI PENGIRIMAN</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">No. Pesanan:</div>
                    <div class="info-value">{{ $order->order_number }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Kurir:</div>
                    <div class="info-value">{{ $order->shipping_courier ?? 'JNE' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Layanan:</div>
                    <div class="info-value">REGULER</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Tanggal Kirim:</div>
                    <div class="info-value">{{ now()->format('d/m/Y') }}</div>
                </div>
            </div>
        </div>
        
        <!-- Package Info -->
        <div class="package-info">
            <div class="package-item">
                <div class="package-label">Berat</div>
                <div class="package-value">1.0 Kg</div>
            </div>
            <div class="package-item">
                <div class="package-label">Dimensi</div>
                <div class="package-value">20x15x10</div>
            </div>
            <div class="package-item">
                <div class="package-label">Jumlah</div>
                <div class="package-value">{{ $order->items->count() }}</div>
            </div>
        </div>
        
        <!-- Warning -->
        <div class="warning">
            ⚠ HATI-HATI: BARANG MUDAH PECAH
        </div>
        
        <!-- Footer -->
        <div class="footer">
            Dicetak: {{ now()->format('d/m/Y H:i') }} | Halaman 1/2
        </div>
    </div>
    
    <div class="page-break"></div>
    
    <!-- Label 2: Package Contents -->
    <div class="label-container">
        <div class="header">
            <div class="company-name">TOKO ONLINE</div>
            <div class="company-address">INVENTORY LIST</div>
        </div>
        
        <div class="title">DAFTAR BARANG DALAM PAKET</div>
        
        <!-- Order Info -->
        <div class="section">
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">No. Pesanan:</div>
                    <div class="info-value">{{ $order->order_number }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Customer:</div>
                    <div class="info-value">{{ $order->user->name }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Tanggal:</div>
                    <div class="info-value">{{ $order->created_at->format('d/m/Y') }}</div>
                </div>
            </div>
        </div>
        
        <!-- Items List -->
        <div class="section">
            <div class="section-title">ITEM PESANAN</div>
            <table style="width: 100%; border-collapse: collapse; font-size: 9px;">
                <thead>
                    <tr style="background-color: #f0f0f0;">
                        <th style="border: 1px solid #ccc; padding: 3px; width: 5%;">No</th>
                        <th style="border: 1px solid #ccc; padding: 3px; width: 60%;">Nama Produk</th>
                        <th style="border: 1px solid #ccc; padding: 3px; width: 10%;">Qty</th>
                        <th style="border: 1px solid #ccc; padding: 3px; width: 25%;">Cek</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $index => $item)
                        <tr>
                            <td style="border: 1px solid #ccc; padding: 3px; text-align: center;">{{ $index + 1 }}</td>
                            <td style="border: 1px solid #ccc; padding: 3px;">{{ $item->product->name }}</td>
                            <td style="border: 1px solid #ccc; padding: 3px; text-align: center;">{{ $item->quantity }}</td>
                            <td style="border: 1px solid #ccc; padding: 3px; text-align: center;">□</td>
                        </tr>
                    @endforeach
                    @for($i = count($order->items); $i < 8; $i++)
                        <tr>
                            <td style="border: 1px solid #ccc; padding: 3px; text-align: center;">{{ $i + 1 }}</td>
                            <td style="border: 1px solid #ccc; padding: 3px;">&nbsp;</td>
                            <td style="border: 1px solid #ccc; padding: 3px; text-align: center;">&nbsp;</td>
                            <td style="border: 1px solid #ccc; padding: 3px; text-align: center;">□</td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
        
        <!-- Packing Checklist -->
        <div class="section">
            <div class="section-title">CHECKLIST PENGEMASAN</div>
            <div style="font-size: 9px;">
                <div>□ Semua item sudah dicek</div>
                <div>□ Bubble wrap untuk barang pecah belah</div>
                <div>□ Invoice dimasukkan dalam paket</div>
                <div>□ Paket disegel dengan packing tape</div>
                <div>□ Label ditempel dengan benar</div>
            </div>
        </div>
        
        <!-- Barcode -->
        <div class="barcode">
            <div class="barcode-text">*{{ $order->order_number }}*</div>
            <div class="tracking-number">{{ $order->tracking_number ?? 'TBA' }}</div>
        </div>
        
        <!-- Stamp Area -->
        <div class="stamp-area">
            TANDA TANGAN & STAMPEL KURIR
        </div>
        
        <!-- Footer -->
        <div class="footer">
            Dicetak: {{ now()->format('d/m/Y H:i') }} | Halaman 2/2
        </div>
    </div>
</body>
</html>