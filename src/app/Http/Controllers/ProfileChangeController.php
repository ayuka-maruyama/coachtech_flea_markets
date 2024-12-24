<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;

class ProfileChangeController extends Controller
{
    public function open($item_id)
    {
        if (!$user = Auth::user()) {
            return redirect()->route('login.open');
        }

        $item = Item::findOrFail($item_id);
        $profile = $user->profile;

        return view('profile-change', compact('user', 'item', 'profile'));
    }
}
