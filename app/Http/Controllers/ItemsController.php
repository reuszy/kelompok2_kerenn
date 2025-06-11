<?php
namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;

class ItemsController extends Controller
{
    public function index()
    {
        $items = Item::with('category')->get();
        return view('items.index', compact('items'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('items.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kode_barang' => 'required|string|unique:items,kode_barang',
            'kategori_id' => 'required|exists:categories,id',
            'jumlah' => 'required|integer',
            'satuan' => 'required|string|max:100',
            'lokasi' => 'nullable|string|max:255',
            'kondisi' => 'required|in:baik,rusak ringan,rusak berat',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png',
            'status' => 'required|in:tersedia,dipinjam',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('foto_barang', 'public');
        }

        Item::create($validated);

        return redirect()->route('items.index')->with('success', 'Item berhasil ditambahkan');
    }

    public function edit(Item $item)
    {
        $categories = Category::all();
        return view('items.edit', compact('item', 'categories'));
    }

    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kode_barang' => 'required|string|unique:items,kode_barang,' . $item->id,
            'kategori_id' => 'required|exists:categories,id',
            'jumlah' => 'required|integer',
            'satuan' => 'required|string|max:100',
            'lokasi' => 'nullable|string|max:255',
            'kondisi' => 'required|in:baik,rusak ringan,rusak berat',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png',
            'status' => 'required|in:tersedia,dipinjam',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('foto_barang', 'public');
        }

        $item->update($validated);

        return redirect()->route('items.index')->with('success', 'Item berhasil diupdate');
    }

    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('items.index')->with('success', 'Item berhasil dihapus');
    }
}
