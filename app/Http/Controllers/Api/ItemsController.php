<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemsController extends Controller
{
    public function index()
    {
        return response()->json(Item::with('category')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kode_barang' => 'required|string|unique:items,kode_barang',
            'kategori_id' => 'required|exists:categories,id',
            'jumlah' => 'required|integer|min:0',
            'satuan' => 'required|string',
            'lokasi' => 'nullable|string',
            'kondisi' => 'required|in:baik,rusak ringan,rusak berat',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|string',
            'status' => 'required|in:tersedia,dipinjam',
        ]);

        $item = Item::create($request->all());
        return response()->json($item, 201);
    }

    public function show(Item $item)
    {
        return response()->json($item->load('category'));
    }

    public function update(Request $request, Item $item)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kode_barang' => 'required|string|unique:items,kode_barang,' . $item->id,
            'kategori_id' => 'required|exists:categories,id',
            'jumlah' => 'required|integer|min:0',
            'satuan' => 'required|string',
            'lokasi' => 'nullable|string',
            'kondisi' => 'required|in:baik,rusak ringan,rusak berat',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|string',
            'status' => 'required|in:tersedia,dipinjam',
        ]);

        $item->update($request->all());
        return response()->json($item);
    }

    public function destroy(Item $item)
    {
        $item->delete();
        return response()->json(null, 204);
    }
}
