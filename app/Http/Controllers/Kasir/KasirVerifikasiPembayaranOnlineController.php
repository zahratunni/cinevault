<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KasirVerifikasiPembayaranOnlineController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'Pending');
        
        $pembayarans = Pembayaran::with(['pemesanan.jadwal.film', 'pemesanan.user', 'verifiedBy'])
            ->where('status_pembayaran', $status)
            ->whereIn('metode_bayar', ['E-Wallet', 'Transfer Bank', 'Online'])
            ->orderBy('tanggal_pembayaran', 'desc')
            ->paginate(15);

        $stats = [
            'pending' => Pembayaran::where('status_pembayaran', 'Pending')
                ->whereIn('metode_bayar', ['E-Wallet', 'Transfer Bank', 'Online'])
                ->count(),
            'lunas' => Pembayaran::where('status_pembayaran', 'Lunas')
                ->whereIn('metode_bayar', ['E-Wallet', 'Transfer Bank', 'Online'])
                ->count(),
            'gagal' => Pembayaran::where('status_pembayaran', 'Gagal')
                ->whereIn('metode_bayar', ['E-Wallet', 'Transfer Bank', 'Online'])
                ->count(),
        ];

        return view('kasir.verifikasi-online', compact('pembayarans', 'stats', 'status'));
    }

    public function show($id)
    {
        $pembayaran = Pembayaran::with([
            'pemesanan.jadwal.film',
            'pemesanan.jadwal.studio',
            'pemesanan.user',
            'pemesanan.detailPemesanans.kursi',
            'verifiedBy'
        ])->findOrFail($id);

        return view('kasir.verifikasi-detail', compact('pembayaran'));
    }

    public function approve(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:500'
        ]);

        $pembayaran = Pembayaran::findOrFail($id);

        if ($pembayaran->isVerified()) {
            return back()->with('error', 'Pembayaran sudah diverifikasi sebelumnya!');
        }

        $pembayaran->update([
            'status_pembayaran' => 'Lunas',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
            'admin_notes' => $request->admin_notes,
            'rejection_reason' => null
        ]);

        $pembayaran->pemesanan->update([
            'status_pemesanan' => 'Confirmed'
        ]);

        return redirect()
            ->route('kasir.verifikasi-online.index')
            ->with('success', 'Pembayaran berhasil diverifikasi! Customer akan menerima e-ticket.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $pembayaran = Pembayaran::findOrFail($id);

        if ($pembayaran->isVerified()) {
            return back()->with('error', 'Pembayaran sudah diverifikasi sebelumnya!');
        }

        $pembayaran->update([
            'status_pembayaran' => 'Gagal',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
            'rejection_reason' => $request->rejection_reason,
            'admin_notes' => $request->admin_notes
        ]);

        return redirect()
            ->route('kasir.verifikasi-online.index')
            ->with('warning', 'Pembayaran ditolak. Customer dapat upload bukti ulang.');
    }
}