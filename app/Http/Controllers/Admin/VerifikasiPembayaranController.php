<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VerifikasiPembayaranController extends Controller
{
    /**
     * Display a listing of payments pending verification
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'Pending');
        
        $pembayarans = Pembayaran::with(['pemesanan', 'kasir', 'verifiedBy'])
            ->where('status_pembayaran', $status)
            ->whereIn('metode_bayar', ['E-Wallet', 'Transfer Bank']) // Hanya yang perlu verifikasi
            ->orderBy('tanggal_pembayaran', 'desc')
            ->paginate(15);

        // Hitung statistik
        $stats = [
            'pending' => Pembayaran::pending()->whereIn('metode_bayar', ['E-Wallet', 'Transfer Bank'])->count(),
            'lunas' => Pembayaran::lunas()->whereIn('metode_bayar', ['E-Wallet', 'Transfer Bank'])->count(),
            'gagal' => Pembayaran::gagal()->whereIn('metode_bayar', ['E-Wallet', 'Transfer Bank'])->count(),
        ];

        return view('admin.verifikasi.index', compact('pembayarans', 'stats', 'status'));
    }

    /**
     * Show payment detail for verification
     */
    public function show($id)
    {
        $pembayaran = Pembayaran::with(['pemesanan', 'kasir', 'verifiedBy'])
            ->findOrFail($id);

        return view('admin.verifikasi.show', compact('pembayaran'));
    }

    /**
     * Approve payment
     */
    public function approve(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:500'
        ]);

        $pembayaran = Pembayaran::findOrFail($id);

        // Cek apakah sudah diverifikasi
        if ($pembayaran->isVerified()) {
            return back()->with('error', 'Pembayaran sudah diverifikasi sebelumnya!');
        }

        // Update status
        $pembayaran->update([
            'status_pembayaran' => 'Lunas',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
            'admin_notes' => $request->admin_notes,
            'rejection_reason' => null
        ]);

        // TODO: Kirim notifikasi ke customer
        // TODO: Generate e-ticket

        return redirect()
            ->route('admin.verifikasi.index')
            ->with('success', 'Pembayaran berhasil diverifikasi! Status: LUNAS');
    }

    /**
     * Reject payment
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $pembayaran = Pembayaran::findOrFail($id);

        // Cek apakah sudah diverifikasi
        if ($pembayaran->isVerified()) {
            return back()->with('error', 'Pembayaran sudah diverifikasi sebelumnya!');
        }

        // Update status
        $pembayaran->update([
            'status_pembayaran' => 'Gagal',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
            'rejection_reason' => $request->rejection_reason,
            'admin_notes' => $request->admin_notes
        ]);

        // TODO: Kirim notifikasi ke customer

        return redirect()
            ->route('admin.verifikasi.index')
            ->with('warning', 'Pembayaran ditolak. Customer akan menerima notifikasi.');
    }

    /**
     * API endpoint untuk polling status (untuk customer waiting page)
     */
    public function checkStatus($id)
    {
        $pembayaran = Pembayaran::find($id);

        if (!$pembayaran) {
            return response()->json(['status' => 'not_found'], 404);
        }

        return response()->json([
            'status' => $pembayaran->status_pembayaran,
            'verified_at' => $pembayaran->verified_at,
            'rejection_reason' => $pembayaran->rejection_reason
        ]);
    }
}