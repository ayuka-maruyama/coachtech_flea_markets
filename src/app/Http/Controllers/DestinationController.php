<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestinationRequest;
use App\Models\Destination;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class DestinationController extends Controller
{
    public function showDestinationForm($item_id)
    {
        // profile-change.blade.phpに初期登録したProfileデータを渡す
        if (!$user = Auth::user()) {
            return redirect()->route('login.form.show');
        }

        $item = Item::findOrFail($item_id);
        $destination = $user->profile;

        return view('destination', compact('user', 'item', 'destination'));
    }

    public function createOrUpdate(DestinationRequest $request, $item_id)
    {
        $user = Auth::user();

        try {
            $destination = Destination::firstOrNew(
                ['user_id' => $user->user_id, 'item_id' => $item_id]
            );

            $destination->fill($request->only(['postal_number', 'address', 'building']));
            $destination->save();

            return redirect()
                ->route('purchase', ['item_id' => $item_id])
                ->with('message', '送付先を変更しました');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['error' => '送付先の変更に失敗しました。']);
        }
    }}
