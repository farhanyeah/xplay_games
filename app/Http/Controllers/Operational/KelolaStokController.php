<?php

namespace App\Http\Controllers\Operational;

use App\Http\Controllers\Controller;
use App\Models\Stok;
use Illuminate\Http\Request;

class KelolaStokController extends Controller
{
    public function index(Request $request)
    {
        $query = Stok::query();

        if ($request->search) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        if ($request->kategori) {
            $query->where('kategori', $request->kategori);
        }

        $stoks = $query->orderBy('kategori')->orderBy('nama')->paginate(10);

        return view('operational.kelola-stok', compact('stoks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:255',
            'kategori' => 'required|in:makanan,minuman,lainnya',
            'harga'    => 'required|integer|min:0',
            'stok'     => 'required|integer|min:0',
            'satuan'   => 'required|in:pcs,botol,kaleng,bungkus,pak',
        ]);

        Stok::create($request->only('nama', 'kategori', 'harga', 'stok', 'satuan'));

        return redirect()->route('operational.stok')
            ->with('success', 'Item stok berhasil ditambahkan.');
    }

    public function update(Request $request, Stok $stok)
    {
        $request->validate([
            'nama'     => 'required|string|max:255',
            'kategori' => 'required|in:makanan,minuman,lainnya',
            'harga'    => 'required|integer|min:0',
            'stok'     => 'required|integer|min:0',
            'satuan'   => 'required|in:pcs,botol,kaleng,bungkus,pak',
        ]);

        $stok->update($request->only('nama', 'kategori', 'harga', 'stok', 'satuan'));

        return redirect()->route('operational.stok')
            ->with('success', 'Item stok berhasil diupdate.');
    }

    public function restock(Request $request, Stok $stok)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1',
        ]);

        $stok->increment('stok', $request->jumlah);

        return redirect()->route('operational.stok')
            ->with('success', 'Restock berhasil. Stok ' . $stok->nama . ' bertambah ' . $request->jumlah . ' ' . $stok->satuan . '.');
    }

    public function destroy(Stok $stok)
    {
        $stok->delete();

        return redirect()->route('operational.stok')
            ->with('success', 'Item stok berhasil dihapus.');
    }
}