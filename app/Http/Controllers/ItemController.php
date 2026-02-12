<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use App\Models\Confirmation;
use Illuminate\Support\Facades\Storage; 


class ItemController extends Controller
{
    public function index()
    {
        $foundItems = Item::where('type', 'found')
            ->where('status', 'posted')
            ->latest()
            ->take(5)
            ->get();

        $lostItems = Item::where('type', 'lost')
            ->where('status', 'posted')
            ->latest()
            ->take(5)
            ->get();

        $totalFound = Item::where('type', 'found')->count();
        $totalLost = Item::where('type', 'lost')->count();
        $totalReturned = Item::where('status', 'completed')->count();

        return view('items.index', compact(
            'foundItems',
            'lostItems',
            'totalFound',
            'totalLost',
            'totalReturned'
        ));
    }

    public function createFound()
    {
        $categories = Category::all();
        return view('items.create_found', compact('categories'));
    }

    public function createLost()
    {
        $categories = Category::all();
        return view('items.create_lost', compact('categories'));
    }

    private function storeItem(Request $request, string $type)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'location' => 'required|string|max:255',
            'date' => 'required|date',
            'photo' => 'nullable|image|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            if (!$request->file('photo')->isValid()) {
                dd('File upload tidak valid');
            }

            $photo = $request->file('photo');
            $filename = uniqid() . '.' . $photo->getClientOriginalExtension();
            $photo->move(public_path('storage/items'), $filename);
            $photoPath = 'items/' . $filename;


            if (!$photoPath) {
                dd('Gagal menyimpan file ke storage');
            }
        }

        Item::create([
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'type' => $type,
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'date' => $request->date,
            'photo' => $photoPath,
            'status' => 'posted',
        ]);

        return redirect()->route('home')->with('success', 'Laporan berhasil dikirim');
    }

    public function foundList(Request $request)
    {
        $query = Item::where('type', 'found')
            ->where('status', 'posted');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $items = $query->latest()->paginate(12);

        $categories = Category::all();

        return view('items.found_list', compact('items', 'categories'));
    }

    public function lostList(Request $request)
    {
        $query = Item::where('type', 'lost')
            ->where('status', 'posted');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $items = $query->latest()->paginate(12);

        $categories = Category::all();

        return view('items.lost_list', compact('items', 'categories'));
    }

    public function edit(Item $item)
    {
        // hanya uploader
        if ($item->user_id !== auth()->id()) {
            abort(403);
        }

        // tidak boleh edit jika sudah ada konfirmasi
        if ($item->confirmations()->where('confirmed', 1)->exists()) {
            return back()->with('error', 'Laporan tidak bisa diedit karena sudah ada konfirmasi.');
        }

        $categories = Category::all();

        return view('items.edit', compact('item', 'categories'));
}

public function update(Request $request, Item $item)
{
    if ($item->user_id !== auth()->id()) {
        abort(403);
    }

    if ($item->confirmations()->where('confirmed', 1)->exists()) {
        return back()->with('error', 'Laporan tidak bisa diedit.');
    }

    $request->validate([
        'title' => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
        'description' => 'nullable|string',
        'location' => 'required|string|max:255',
        'date' => 'required|date',
        'photo' => 'nullable|image|max:2048',
    ]);

    if ($request->hasFile('photo')) {

    if ($item->photo && Storage::disk('public')->exists($item->photo)) {
        Storage::disk('public')->delete($item->photo);
    }

    $photo = $request->file('photo');
    $filename = uniqid() . '.' . $photo->getClientOriginalExtension();
    $photo->storeAs('items', $filename, 'public');

    $item->photo = 'items/' . $filename;
}


    $item->update([
        'title' => $request->title,
        'category_id' => $request->category_id,
        'description' => $request->description,
        'location' => $request->location,
        'date' => $request->date,
    ]);

    return redirect()
        ->route('items.show', $item)
        ->with('success', 'Laporan berhasil diperbarui.');
}

public function destroy(Item $item)
{
    if ($item->user_id !== auth()->id()) {
        abort(403);
    }

    if ($item->confirmations()->where('confirmed', 1)->exists()) {
        return back()->with('error', 'Laporan tidak bisa dihapus karena sudah ada konfirmasi.');
    }

    if ($item->photo && Storage::disk('public')->exists($item->photo)) {
        Storage::disk('public')->delete($item->photo);
    }

    $item->confirmations()->delete();
    $item->delete();

    return redirect()->route('home')->with('success', 'Laporan berhasil dihapus.');
}


    public function confirmOwner(Item $item)
    {
        $user = Auth::user();

        if ($user->id === $item->user_id) {
            abort(403);
        }

        $confirmation = Confirmation::firstOrCreate(
            [
                'item_id' => $item->id,
                'user_id' => $user->id,
            ],
            [
                'confirmed' => 0,
            ]
        );

        if ($confirmation->confirmed) {
            return back()->with('info', 'Anda sudah mengkonfirmasi.');
        }

        $confirmation->update(['confirmed' => 1]);

       $item->update(['status' => 'posted']);


        return back()->with('success', 'Klaim berhasil. Menunggu konfirmasi penemu.');
    }

    public function confirmFinder(Item $item)
{
    $user = Auth::user();

    if ($user->id !== $item->user_id) {
        abort(403);
    }

    $ownerConfirmed = $item->confirmations()
        ->where('user_id', '!=', $item->user_id)
        ->where('confirmed', 1)
        ->exists();

    if (!$ownerConfirmed) {
        return back()->with('error', 'Belum ada pemilik yang mengklaim barang.');
    }

    $confirmation = Confirmation::firstOrCreate(
        [
            'item_id' => $item->id,
            'user_id' => $user->id,
        ],
        [
            'confirmed' => 0,
        ]
    );

    if ($confirmation->confirmed) {
        return back()->with('info', 'Anda sudah mengkonfirmasi.');
    }

    $confirmation->update(['confirmed' => 1]);

    if (
        $item->confirmations()->where('confirmed', 1)->count() >= 2
    ) {
        $item->update(['status' => 'completed']);
    }

    return back()->with('success', 'Barang selesai & completed.');
}


    public function storeFound(Request $request)
    {
        return $this->storeItem($request, 'found');
    }

    public function storeLost(Request $request)
    {
        return $this->storeItem($request, 'lost');
    }

    public function show(Item $item)
    {
        $categories = Category::all();
        return view('items.show', compact('item', 'categories'));
    }

}
