<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Confirmation;

class ConfirmationController extends Controller
{
    public function confirm(Item $item)
    {
        $userId = auth()->id();

        // Simpan / update konfirmasi user
        Confirmation::updateOrCreate(
            [
                'item_id' => $item->id,
                'user_id' => $userId,
            ],
            [
                'confirmed' => true,
            ]
        );

        // Hitung konfirmasi
        $confirmedCount = Confirmation::where('item_id', $item->id)
            ->where('confirmed', true)
            ->count();

        // Jika 2 user sudah konfirmasi
        if ($confirmedCount >= 2) {
            $item->update([
                'status' => 'completed',
            ]);
        }

        return back()->with('success', 'Konfirmasi berhasil');
    }
}

