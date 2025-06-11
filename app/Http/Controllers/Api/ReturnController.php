<?php

namespace App\Http\Controllers\Api;

use App\Models\Loans;
use App\Models\LoanItem;
use App\Models\Returns;
use App\Models\Item;
use App\Http\Controllers\Controller;
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
            ->get();
        } else {
            // Kalau admin, ambil semua
            $returns = Returns::with('loan.user', 'loan.loanItems.item')->latest()->get();
        }
        // $returns = Returns::with('loan.user', 'loan.loanItems.item')->latest()->get();
        
        return response()->json($returns);
    }

    // GET /api/returns/loan/{loan_id}
    public function create($loan_id)
    {
        $loan = Loans::with(['user', 'loanItems.item'])->findOrFail($loan_id);
        return response()->json($loan);
    }

    // POST /api/returns
    public function store(Request $request)
    {
        $request->validate([
            'loan_id' => 'required|exists:loans,id',
            'tanggal_pengembalian' => 'required|date',
            'kondisi_pengembalian' => 'nullable|string',
            'catatan' => 'nullable|string',
            'denda' => 'nullable|numeric|min:0',
        ]);

        $return = Returns::create([
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

        Loans::where('id', $request->loan_id)->update([
            'status' => $return,
            'tanggal_pengembalian' => $request->tanggal_pengembalian,
        ]);

        return response()->json(['message' => 'Pengembalian berhasil disimpan.', 'data' => $return], 201);
    }
}