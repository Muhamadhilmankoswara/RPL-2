<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Item;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $chats = Chat::where('user_one', $user->id)
            ->orWhere('user_two', $user->id)
            ->with([
                'item',
                'messages' => function ($q) {
                    $q->latest();
                }
            ])
            ->latest()
            ->get();

        return view('chat.index', compact('chats'));
    }


    public function start(Item $item)
    {
        $authUser = Auth::user();

        if ($item->user_id === $authUser->id) {
            return redirect()->back()->with('error', 'Anda tidak bisa chat ke barang Anda sendiri.');
        }

        $chat = Chat::where('item_id', $item->id)
            ->where(function ($q) use ($authUser, $item) {
                $q->where('user_one', $authUser->id)
                ->where('user_two', $item->user_id);
            })
            ->orWhere(function ($q) use ($authUser, $item) {
                $q->where('user_one', $item->user_id)
                ->where('user_two', $authUser->id);
            })
            ->first();

        if (!$chat) {
            $chat = Chat::create([
                'item_id'  => $item->id,
                'user_one' => $authUser->id,
                'user_two' => $item->user_id,
            ]);
        }

        // ✅ JANGAN return view
        return redirect()->route('chat.show', $chat);
    }

    public function show(Chat $chat)
    {
        $authUser = Auth::user();

        // Security
        if ($chat->user_one !== $authUser->id && $chat->user_two !== $authUser->id) {
            abort(403);
        }

        // Lawan bicara
        $otherUserId = $chat->user_one === $authUser->id
            ? $chat->user_two
            : $chat->user_one;

        $otherUser = \App\Models\User::findOrFail($otherUserId);

        // 🔥 INI YANG SEBELUMNYA HILANG
        $messages = $chat->messages()
            ->with('sender')
            ->orderBy('created_at')
            ->get();

        return view('chat.show', compact(
            'chat',
            'otherUser',
            'messages'
        ));
    }

    public function send(Request $request, Chat $chat)
    {
        $authUser = Auth::user();

        // security: cuma anggota chat yang boleh kirim
        if ($chat->user_one !== $authUser->id && $chat->user_two !== $authUser->id) {
            abort(403);
        }

        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        Message::create([
            'chat_id' => $chat->id,
            'sender_id' => $authUser->id,
            'message' => $request->message,
            'is_read' => false,
        ]);

        return redirect()->route('chat.show', $chat);
    }





}
