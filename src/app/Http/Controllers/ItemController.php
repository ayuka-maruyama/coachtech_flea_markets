<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Favorite;
use App\Models\Item;


class ItemController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'suggest');
        $items = collect();

        $user = Auth::user();

        if ($tab === 'suggest') {
            if ($user) {
                $items = Item::where('user_id', '!=', $user->id)->get();
            } else {
                $items = Item::all();
            }
        } elseif ($tab === 'mylist') {
            if ($user) {
                $favoriteItemIds = Favorite::where('user_id', $user->id)->pluck('item_id');
                $items = Item::whereIn('item_id', $favoriteItemIds)->get();
            }
        }

        return view('item', ['items' => $items, 'tab' => $tab]);
    }
}
