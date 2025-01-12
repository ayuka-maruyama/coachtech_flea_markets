<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExhibitionRequest;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class SellController extends Controller
{
    public function showSellForm()
    {
        if (!$user = Auth::user()) {
            return redirect()->route('login.form.show');
        }

        if (!$user->profile) {
            return redirect()->route('profile.open')->with('message', 'プロフィールを設定してください');
        }

        $categories = Category::all();

        return view('sell', compact('user', 'categories'));
    }

    public function store(ExhibitionRequest $request)
    {
        $user = Auth::user();

        $item = Item::create([
            'item_name' => $request->item_name,
            'brand' => 'no brand',
            'price' => $request->price,
            'description' => $request->description,
            'condition' => $request->condition,
            'item_image' => '',
            'stock_status' => 0,
            'user_id' => $user->user_id,
        ]);

        $item->categories()->sync($request->category_ids);

        $extension = $request->file('item_image')->getClientOriginalExtension();
        $fileName = 'Item' . Auth::id() . '_' . time() . '.' . $extension;

        $filePath = $request->file('item_image')->storeAs('item_image', $fileName, 'public');

        $item->item_image = '/storage/item_image/' . $fileName;
        $item->save();

        return redirect('/mypage?tab=sell')->with('success', '商品が正常に登録されました。');
    }
}
