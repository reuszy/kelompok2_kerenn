<?php

namespace App\Http\Controllers;

use App\Models\Loans;
use App\Models\LoanItem;
use App\Models\Item;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;    

class LoansController extends Controller
{
    // Tampilkan list peminjaman
    public function index()
    {
        // Eager load user dan loanItems + item dalam loanItems
        // $loans = Loans::with(['user', 'loanItems.item'])->paginate(10);
        if (Auth::user()->role === 'peminjam') {
            // Hanya ambil data milik peminjam yg sedang login
            $loans = Loans::with(['user', 'loanItems.item'])
                        ->where('user_id', Auth::id())
                        ->paginate(10);
        } else {
            // Kalau admin, ambil semua
            $loans = Loans::with(['user', 'loanItems.item'])->paginate(10);
        }
        return view('loans.index', compact('loans'));
    }

    // Tampilkan form tambah peminjaman baru
    public function create()
    {
        $users = User::where('role','peminjam')->get();
        $items = Item::where('jumlah','>=','1   ')->get();
        return view('loans.create', compact('users', 'items'));
    }

    // Simpan data peminjaman baru
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

        // Generate kode peminjaman unik
        $kodePeminjaman = 'PNJ-' . Str::upper(Str::random(8));

        if (Auth::user()->role === 'peminjam') {
            $status = 'pending';
        }else{
            $status = 'loaned';
        }

        $loan = Loans::create([
            'user_id' => $request->user_id,
            'kode_peminjaman' => $kodePeminjaman,
            'tanggal_peminjaman' => $request->tanggal_peminjaman,
            'status' => $status,
            'keterangan' => $request->keterangan,
        ]);

        // Simpan loan items
        foreach ($request->items as $itemData) {
            $loan->loanItems()->create([
                'item_id' => $itemData['item_id'],
                'jumlah' => $itemData['jumlah'],
                'kondisi_saat_dipinjam' => $itemData['kondisi_saat_dipinjam'] ?? null,
            ]);

            // Item::where('id', $itemData['item_id'])->update([
            //     'status' => 'dipinjam'
            // ]);
               // Ambil data item
                $item = Item::find($itemData['item_id']);

                // Cek stok cukup atau tidak
                if ($item->jumlah < $itemData['jumlah']) {
                    // return redirect()->back()->withErrors(['Stok tidak cukup untuk item ' . $item->nama_barang]);
                    return redirect()->route('loans.index')->with('error', 'Stok tidak cukup untuk item '.$item->nama_barang);

                }

                // Kurangi stok
                $item->jumlah -= $itemData['jumlah'];

                // Atur status berdasarkan stok baru
                $item->status = $item->jumlah <= 0 ? 'dipinjam' : 'tersedia';

                // Simpan perubahan
                $item->save();
        }

        return redirect()->route('loans.index')->with('success', 'Peminjaman berhasil dibuat.');
    }

    // Tampilkan detail peminjaman
    public function show(Loans $loan)
    {
        $loan->load(['user', 'loanItems.item']);
        return view('loans.show', compact('loan'));
    }

    // Form edit peminjaman
    public function edit(Loans $loan)
    {
        // $loan = Loan::with('loanItems')->findOrFail($id);
        $loan->load('loanItems');
        // $loan->load('loanItems.barang');
        $LoanItem = LoanItem::all();
        $users = User::all();
        $items = Item::all();
        return view('loans.edit', compact('loan','LoanItem', 'users', 'items'));
    }

    // Update data peminjaman
    public function update(Request $request, Loans $loan)
    {
        // dd($loan);
        // $request->validate([
        //     'user_id' => 'required|exists:users,id',
        //     'tanggal_peminjaman' => 'required|date',
        //     'tanggal_pengembalian' => 'nullable|date|after_or_equal:tanggal_peminjaman',
        //     'status' => 'required|in:pending,approved,returned,late',
        //     'keterangan' => 'nullable|string',
        //     'items' => 'required|array',
        //     'items.*.id' => 'nullable|exists:loan_items,id',
        //     'items.*.item_id' => 'required|exists:items,id',
        //     'items.*.jumlah' => 'required|integer|min:1',
        //     'items.*.kondisi_saat_dipinjam' => 'nullable|string',
        // ]);

        $loan->update([
            'user_id' => $request->user_id,
            'tanggal_peminjaman' => $request->tanggal_peminjaman,
            'tanggal_pengembalian' => $request->tanggal_pengembalian,
            'status' => 'pending',
            'keterangan' => $request->keterangan,
        ]);

        // Update loan items (simple approach: hapus semua dulu, baru insert ulang)
        
       if ($request->has('items')) {
            $loan->loanItems()->delete();
            foreach ($request->items as $itemData) {
                $loan->loanItems()->create([
                    'item_id' => $itemData['item_id'],
                    'jumlah' => $itemData['jumlah'],
                    'kondisi_saat_dipinjam' => $itemData['kondisi_saat_dipinjam'] ?? null,
                ]);
            }
        }

        return redirect()->route('loans.index')->with('success', 'Peminjaman berhasil diperbarui.');
    }

    // Approval
    public function approval(Loans $loan)
    {
        $loan->update([
            'status' => 'loaned'
        ]);
        return redirect()->route('loans.index')->with('success', 'Peminjaman berhasil di approve.');
    }

    // Approval Return
    public function approval_return(Loans $loan)
    {
        $loan->update([
            'status' => 'returned'
        ]);
        return redirect()->route('loans.index')->with('success', 'Pengembalian berhasil di approve.');
    }

    // Hapus peminjaman
    public function destroy(Loans $loan)
    {
        $loan->delete();
        return redirect()->route('loans.index')->with('success', 'Peminjaman berhasil dihapus.');
    }
}
