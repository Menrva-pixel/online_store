<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Packing Slip - {{ $order->order_number }}</title>
    <style>
        @page { margin: 0; }
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 20px; }
        .company-name { font-size: 24px; font-weight: bold; color: #333; }
        .company-address { font-size: 12px; color: #666; margin-top: 5px; }
        .title { font-size: 20px; font-weight: bold; text-align: center; margin: 20px 0; }
        .section { margin-bottom: 20px; }
        .section-title { font-size: 16px; font-weight: bold; border-bottom: 1px solid #ddd; padding-bottom: 5px; margin-bottom: 10px; }
        .info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; }
        .info-item { margin-bottom: 8px; }
        .info-label { font-weight: bold; font-size: 12px; color: #666; }
        .info-value { font-size: 13px; }
        .table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .table th { background-color: #f8f9fa; border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 12px; }
        .table td { border: 1px solid #ddd; padding: 8px; font-size: 12px; }
        .total-row { font-weight: bold; background-color: #f8f9fa; }
        .footer { margin-top: 40px; font-size: 11px; color: #666; text-align: center; }
        .barcode { text-align: center; margin: 20px 0; }
        .barcode img { max-width: 200px; }
        .notes { margin-top: 20px; padding: 10px; background-color: #f8f9fa; border: 1px dashed #ddd; font-size: 11px; }
        .packed-by { margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; }
        .signature-line { width: 200px; border-top: 1px solid #333; margin-top: 40px; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="company-name">TOKO ONLINE</div>
            <div class="company-address">
                Jl. Contoh No. 123, Kota Contoh, Provinsi Contoh<br>
                Telp: (021) 12345678 | Email: info@tokoonline.com
            </div>
        </div>

        <!-- Title -->
        <div class="title">PACKING SLIP</div>

        <!-- Order Information -->
        <div class="section">
            <div class="section-title">Informasi Pesanan</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">No. Pesanan</div>
                    <div class="info-value">{{ $order->order_number }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Tanggal Pesanan</div>
                    <div class="info-value">{{ $order->created_at->format('d F Y H:i') }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Status</div>
                    <div class="info-value">{{ strtoupper($order->status) }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Metode Pembayaran</div>
                    <div class="info-value">{{ $order->payment_method ?? 'Transfer Bank' }}</div>
                </div>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="section">
            <div class="section-title">Informasi Customer</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Nama Customer</div>
                    <div class="info-value">{{ $order->user->name }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Email</div>
                    <div class="info-value">{{ $order->user->email }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Telepon</div>
                    <div class="info-value">{{ $order->recipient_phone ?? '-' }}</div>
                </div>
            </div>
        </div>

        <!-- Shipping Information -->
        <div class="section">
            <div class="section-title">Informasi Pengiriman</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Nama Penerima</div>
                    <div class="info-value">{{ $order->recipient_name }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Alamat Pengiriman</div>
                    <div class="info-value">{{ $order->shipping_address }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Kurir</div>
                    <div class="info-value">{{ $order->shipping_courier ?? 'Akan ditentukan' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">No. Resi</div>
                    <div class="info-value">{{ $order->tracking_number ?? 'Akan diisi' }}</div>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="section">
            <div class="section-title">Daftar Item</div>
            <table class="table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="45%">Nama Produk</th>
                        <th width="15%">SKU</th>
                        <th width="10%">Qty</th>
                        <th width="25%">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->product->name }}</td>
                            <td>PROD-{{ str_pad($item->product_id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td></td>
                        </tr>
                    @endforeach
                    <!-- Empty rows for checking -->
                    @for($i = count($order->items); $i < 10; $i++)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>

        <!-- Packing Instructions -->
        <div class="section">
            <div class="section-title">Instruksi Pengepakan</div>
            <div class="notes">
                <strong>PERHATIAN:</strong>
                <ol style="margin: 5px 0 0 20px; padding: 0;">
                    <li>Periksa semua item sebelum dikemas</li>
                    <li>Pastikan jumlah sesuai dengan pesanan</li>
                    <li>Gunakan bubble wrap untuk barang pecah belah</li>
                    <li>Tempelkan label pengiriman dengan benar</li>
                    <li>Segel paket dengan packing tape</li>
                </ol>
            </div>
        </div>

        <!-- Barcode -->
        <div class="barcode">
            <div style="font-family: 'Libre Barcode 128', cursive; font-size: 36px;">
                *{{ $order->order_number }}*
            </div>
            <div style="font-size: 12px; margin-top: 5px;">{{ $order->order_number }}</div>
        </div>

        <!-- Packer Information -->
        <div class="packed-by">
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Diproses oleh</div>
                    <div class="signature-line"></div>
                    <div class="info-value" style="text-align: center; margin-top: 5px;">(Nama & Tanda Tangan)</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Tanggal Pengepakan</div>
                    <div class="signature-line"></div>
                    <div class="info-value" style="text-align: center; margin-top: 5px;">(Tanggal)</div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Dokumen ini dicetak pada: {{ now()->format('d F Y H:i:s') }}</p>
            <p>Halaman 1 dari 1</p>
        </div>
    </div>
</body>
</html>