<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function showItemFrom(Request $request)
    {
        $tab = $request->query('tab', 'suggest');
        $items = collect();

        $user = Auth::user();

        if ($tab === 'suggest') {
            if ($user) {
                $items = Item::where('user_id', '!=', $user->user_id)
                    ->orderBy('item_id', 'asc')
                    ->get();
            } else {
                $items = Item::all();
            }
        } elseif ($tab === 'mylist') {
            if ($user) {
                $favoriteItemIds = Favorite::where('user_id', $user->user_id)
                    ->orderBy('item_id', 'asc')
                    ->pluck('item_id');
                $items = Item::whereIn('item_id', $favoriteItemIds)->get();
            }
        }

        return view('item', ['items' => $items, 'tab' => $tab]);
    }
}
