<?php

namespace App\Http\Controllers;

use App\Models\Ulasan;
use Illuminate\Http\Request;

class UlasanController extends Controller
{

    public function index(Request $request)
    {
        $query = Ulasan::with('user')->latest();

        if ($request->filter === 'rating-5') {
            $query->where('rating', 5);
        }

        if ($request->filter === 'rating-4') {
            $query->where('rating', 4);
        }

        if ($request->filter === 'rating-3') {
            $query->where('rating', 3);
        }

        if ($request->filter === 'rating-2') {
            $query->where('rating', 2);
        }

        if ($request->filter === 'rating-1') {
            $query->where('rating', 1);
        }

        if (
            $request->filter === 'my-review'
            && auth()->check()
        ) {
            $query->where('user_id', auth()->id());
        }

        $ulasan = $query->get();

        return view('pages.home', compact('ulasan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'pesan' => ['required', 'string', 'max:500'],
        ]);

        Ulasan::create([
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'pesan' => $request->pesan,
        ]);

        return redirect()->route('home', '#ulasan') 
        ->with('success', 'Ulasan berhasil dikirim, terima kasih!');
    }

    public function destroy(Ulasan $ulasan)
    {
        // hanya pemilik ulasan yang boleh hapus
        if ($ulasan->user_id !== auth()->id()) {
            abort(403);
        }

        $ulasan->delete();

        return redirect()
            ->route('home', ['filter' => 'my-review'])
            ->with('success', 'Ulasan berhasil dihapus.');
    }
}
