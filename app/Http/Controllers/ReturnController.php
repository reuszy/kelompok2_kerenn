<?php

namespace App\Http\Controllers;

use App\Models\Loans;
use App\Models\LoanItem;
use App\Models\Returns;
use App\Models\Item;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ReturnController extends Controller
{
        public function index()
    {
        if (Auth::user()->role === 'peminjam') {
            // Hanya ambil data milik peminjam yg sedang login
            $returns = Returns::with('loan.user', 'loan.loanItems.item')
            ->whereHas('loan', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->paginate(10);
        } else {
            // Kalau admin, ambil semua
            $returns = Returns::with('loan.user', 'loan.loanItems.item')->latest()->paginate(10);
        }
        // $returns = Returns::with('loan.user', 'loan.loanItems.item')->latest()->paginate(10);

        return view('return.index', compact('returns'));
    }

        public function create($loan_id)
    {
        $loan = Loans::with(['user', 'loanItems.item'])->findOrFail($loan_id);

        return view('return.create', compact('loan'));
    }

    public function store(Request $request)
{
    $request->validate([
        'loan_id' => 'required|exists:loans,id',
        'tanggal_pengembalian' => 'required|date',
        'kondisi_pengembalian' => 'nullable|string',
        'catatan' => 'nullable|string',
        'denda' => 'nullable|numeric|min:0',
    ]);

    // Simpan data pengembalian
    Returns::create([
        'loan_id' => $request->loan_id,
        'tanggal_pengembalian' => $request->tanggal_pengembalian,
        'kondisi_pengembalian' => $request->kondisi_pengembalian,
        'catatan' => $request->catatan,
        'denda' => $request->denda ?? 0,
    ]);

    if (Auth::user()->role === 'peminjam') {
        $status = 'Return Approval';
    }else{
        $status = 'returned';
    }
    // Update status pinjaman menjadi 'returned'
    Loans::where('id', $request->loan_id)->update([
        'status' => $status,
        'tanggal_pengembalian' => $request->tanggal_pengembalian,
    ]);

    $loan = Loans::with('loanItems')->findOrFail($request->loan_id);

    foreach ($loan->loanItems as $loanItem) {
        // Ambil item terkait
        $item = Item::find($loanItem->item_id);
    
        // Tambah stok kembali sesuai jumlah yang dipinjam
        $item->jumlah += $loanItem->jumlah;
    
        // Update status berdasarkan stok
        $item->status = $item->jumlah > 0 ? 'tersedia' : 'dipinjam';
    
        $item->save();
    }

    return redirect()->route('loans.index')->with('success', 'Pengembalian berhasil disimpan.');
}

}