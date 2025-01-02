<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\User;
use App\Models\Profile;
use App\Http\Requests\DestinationRequest;

class DestinationController extends Controller
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

    public function update(DestinationRequest $request, $item_id)
    {
        $user = User::with('profile')->find(Auth::id());

        $profile = Profile::find($user->user_id);

        $profile->update([
            'postal_number' => $request->postal_number,
            'address' => $request->address,
            'building' => $request->building,
        ]);

        return redirect()->route('purchase', ['item_id' => $item_id])->with('message', '送付先を変更しました');
    }
}
