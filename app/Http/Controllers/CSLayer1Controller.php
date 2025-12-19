<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PaymentProof;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CSLayer1Controller extends Controller
{
    public function dashboard()
    {
        $pendingPayments = PaymentProof::where('status', 'pending')
            ->with('order.user')
            ->latest()
            ->take(10)
            ->get();

        $verifiedCount = PaymentProof::where('status', 'verified')->count();
        $rejectedCount = PaymentProof::where('status', 'rejected')->count();
        $pendingCount = PaymentProof::where('status', 'pending')->count();

        return view('cs1.dashboard', compact(
            'pendingPayments', 
            'verifiedCount', 
            'rejectedCount', 
            'pendingCount'
        ));
    }

    public function pendingPayments()
    {
        $payments = PaymentProof::where('status', 'pending')
            ->with('order.user')
            ->latest()
            ->paginate(20);

        return view('cs1.payments.pending', compact('payments'));
    }

    public function showPayment(PaymentProof $paymentProof)
    {
        $paymentProof->load(['order.user', 'order.items.product']);
        return view('cs1.payments.show', compact('paymentProof'));
    }

    public function verifyPayment(Request $request, PaymentProof $paymentProof)
    {
        $request->validate([
            'notes' => 'nullable|string|max:500'
        ]);

        $paymentProof->update([
            'status' => 'verified',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
            'verification_notes' => $request->notes
        ]);

        // Update order status
        $paymentProof->order->update([
            'status' => 'processing'
        ]);

        return redirect()->route('cs1.payments.pending')
            ->with('success', 'Pembayaran berhasil diverifikasi.');
    }

    public function rejectPayment(Request $request, PaymentProof $paymentProof)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        $paymentProof->update([
            'status' => 'rejected',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
            'verification_notes' => $request->reason
        ]);

        // Update order status
        $paymentProof->order->update([
            'status' => 'waiting_payment'
        ]);

        return redirect()->route('cs1.payments.pending')
            ->with('success', 'Pembayaran ditolak.');
    }

    public function pendingOrders()
    {
        $orders = Order::where('status', 'waiting_payment')
            ->with(['user', 'paymentProof'])
            ->latest()
            ->paginate(20);

        return view('cs1.orders.pending', compact('orders'));
    }

public function verifiedPayments()
{
    $payments = PaymentProof::where('status', 'verified')
        ->whereNotNull('verified_at')
        ->with(['order.user', 'verifier'])
        ->latest()
        ->paginate(20);

    // Tambahkan relasi verifier jika ada
    if (method_exists(PaymentProof::class, 'verifier')) {
        $payments->load('verifier');
    }

    return view('cs1.payments.verified', compact('payments'));
}

    public function downloadProof(PaymentProof $paymentProof)
    {
        if (!$paymentProof->proof_path || !Storage::disk('public')->exists($paymentProof->proof_path)) {
            abort(404, 'File tidak ditemukan.');
        }

        return Storage::disk('public')->download($paymentProof->proof_path);
    }

    public function viewProof(PaymentProof $paymentProof)
    {
        if (!$paymentProof->proof_path || !Storage::disk('public')->exists($paymentProof->proof_path)) {
            abort(404, 'File tidak ditemukan.');
        }

        $path = Storage::disk('public')->path($paymentProof->proof_path);
        $mime = mime_content_type($path);

        return response()->file($path, [
            'Content-Type' => $mime,
        ]);
    }
}
