<?php

namespace App\Http\Controllers\Api;

use App\Models\Loans;
use App\Models\LoanItem;
use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LoansController extends Controller{
    public function index()
    {
        if (Auth::user()->role === 'peminjam') {
            // Hanya ambil data milik peminjam yg sedang login
            $loans = Loans::with(['user', 'loanItems.item'])
                        ->where('user_id', Auth::id())
                        ->get();
        } else {
            // Kalau admin, ambil semua
            $loans = Loans::with(['user', 'loanItems.item'])->get();
        }
        // $loans = Loans::with(['user', 'loanItems.item'])->latest()->get();
        return response()->json($loans);
    }

    // POST /api/loans
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal_peminjaman' => 'required|date',
            'keterangan' => 'nullable|string',
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.kondisi_saat_dipinjam' => 'nullable|string',
        ]);

        $kodePeminjaman = 'PNJ-' . Str::upper(Str::random(8));

        $loan = Loans::create([
            'user_id' => $request->user_id,
            'kode_peminjaman' => $kodePeminjaman,
            'tanggal_peminjaman' => $request->tanggal_peminjaman,
            'status' => 'loaned',
            'keterangan' => $request->keterangan,
        ]);

        foreach ($request->items as $item) {
            $loan->loanItems()->create([
                'item_id' => $item['item_id'],
                'jumlah' => $item['jumlah'],
                'kondisi_saat_dipinjam' => $item['kondisi_saat_dipinjam'] ?? null,
            ]);
        }

        return response()->json(['message' => 'Peminjaman berhasil disimpan.', 'data' => $loan], 201);
    }

    // GET /api/loans/{id}
    public function show($id)
    {
        $loan = Loans::with(['user', 'loanItems.item'])->find($id);

        if (!$loan) {
            return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        }

        return response()->json($loan);
    }

    // PUT /api/loans/{id}
    public function update(Request $request, $id)
    {
        $loan = Loans::find($id);

        if (!$loan) {
            return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal_peminjaman' => 'required|date',
            'tanggal_pengembalian' => 'nullable|date',
            'status' => 'required|in:pending,approved,returned,late',
            'keterangan' => 'nullable|string',
            'items' => 'nullable|array',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.kondisi_saat_dipinjam' => 'nullable|string',
        ]);

        $loan->update([
            'user_id' => $request->user_id,
            'tanggal_peminjaman' => $request->tanggal_peminjaman,
            'tanggal_pengembalian' => $request->tanggal_pengembalian,
            'status' => $request->status,
            'keterangan' => $request->keterangan,
        ]);

        if ($request->has('items')) {
            $loan->loanItems()->delete();
            foreach ($request->items as $item) {
                $loan->loanItems()->create([
                    'item_id' => $item['item_id'],
                    'jumlah' => $item['jumlah'],
                    'kondisi_saat_dipinjam' => $item['kondisi_saat_dipinjam'] ?? null,
                ]);
            }
        }

        return response()->json(['message' => 'Peminjaman berhasil diupdate.', 'data' => $loan]);
    }

        public function approval(Request $request, Loans $loan)
    {
        if ($loan->status !== 'pending') {
            return response()->json([
                'message' => 'Peminjaman tidak bisa di-approve karena bukan status pending.',
            ], 422);
        }

        $loan->update([
            'status' => 'loaned'
        ]);

        return response()->json([
            'message' => 'Peminjaman berhasil di-approve.',
            'data' => $loan
        ], 200);
    }

        public function approval_returned(Request $request, Loans $loan)
    {
        if ($loan->status !== 'Return Approval') {
            return response()->json([
                'message' => 'Peminjaman tidak bisa di-approve karena bukan status Return Approval.',
            ], 422);
        }

        $loan->update([
            'status' => 'returned'
        ]);

        return response()->json([
            'message' => 'Peminjaman berhasil di-approve.',
            'data' => $loan
        ], 200);
    }


    // DELETE /api/loans/{id}
    public function destroy($id)
    {
        $loan = Loans::find($id);

        if (!$loan) {
            return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        }

        $loan->delete();

        return response()->json(['message' => 'Peminjaman berhasil dihapus.']);
    }
}