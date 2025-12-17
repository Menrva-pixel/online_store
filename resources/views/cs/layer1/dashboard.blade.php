<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CS Layer 1 Dashboard</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .header { background: #fff; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .header h1 { color: #333; margin-bottom: 10px; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; margin: 20px 0; }
        .stat-card { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .stat-card h3 { color: #666; font-size: 14px; margin-bottom: 10px; }
        .stat-card .value { font-size: 32px; font-weight: bold; color: #333; }
        .nav-menu { background: #fff; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .nav-menu a { margin-right: 15px; text-decoration: none; color: #3490dc; font-weight: 500; }
        .nav-menu a:hover { text-decoration: underline; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f8f9fa; }
        .btn { display: inline-block; padding: 8px 16px; background: #3490dc; color: white; text-decoration: none; border-radius: 4px; margin-right: 5px; }
        .btn:hover { background: #2779bd; }
        .btn-success { background: #38c172; }
        .btn-danger { background: #e3342f; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>CS Layer 1 Dashboard</h1>
            <div class="nav-menu">
                <a href="{{ route('cs1.dashboard') }}">Dashboard</a>
                <a href="{{ route('cs1.payments.pending') }}">Pembayaran Pending</a>
                <a href="{{ route('cs1.payments.verified') }}">Pembayaran Terverifikasi</a>
                <a href="{{ route('cs1.orders.pending') }}">Pesanan Menunggu</a>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>

        @if(session('success'))
            <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin: 10px 0;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin: 10px 0;">
                {{ session('error') }}
            </div>
        @endif

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Pembayaran Pending</h3>
                <div class="value">{{ $pendingCount ?? 0 }}</div>
            </div>
            <div class="stat-card">
                <h3>Pembayaran Terverifikasi</h3>
                <div class="value">{{ $verifiedCount ?? 0 }}</div>
            </div>
            <div class="stat-card">
                <h3>Pembayaran Ditolak</h3>
                <div class="value">{{ $rejectedCount ?? 0 }}</div>
            </div>
        </div>

        @if(isset($pendingPayments) && $pendingPayments->count() > 0)
        <div style="background: #fff; padding: 20px; border-radius: 8px; margin: 20px 0;">
            <h2>Pembayaran Pending Terbaru</h2>
            <table>
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Tanggal Upload</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingPayments as $payment)
                    <tr>
                        <td>{{ $payment->order->order_number ?? 'N/A' }}</td>
                        <td>{{ $payment->order->user->name ?? 'N/A' }}</td>
                        <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('cs1.payments.show', $payment) }}" class="btn">Lihat</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</body>
</html>