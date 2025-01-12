<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;
use App\Models\Item;
use App\Http\Requests\ExhibitionRequest;

class SellController extends Controller
{
    public function showSellForm()
    {
        // ログイン状況を確認して、未ログインならログイン画面へ遷移
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

        // ① 商品データを保存
        $item = Item::create([
            'item_name' => $request->item_name,
            'brand' => 'no brand',
            'price' => $request->price,
            'description' => $request->description,
            'condition' => $request->condition,
            'item_image' => '', // 仮置き
            'stock_status' => 0, // 初期値
            'user_id' => $user->user_id,
        ]);

        // ② カテゴリーIDを中間テーブルに保存
        $item->categories()->sync($request->category_ids);

        // ③ 商品画像を保存

        // 新しい画像のファイル名を作成
        $extension = $request->file('item_image')->getClientOriginalExtension();
        $fileName = 'Item' . Auth::id() . '_' . time() . '.' . $extension;

        // 新しい画像を 'item_image' フォルダに保存
        $filePath = $request->file('item_image')->storeAs('item_image', $fileName, 'public');

        // 新しい画像のパスを保存
        $item->item_image = '/storage/item_image/' . $fileName;
        $item->save(); // 画像パスを保存

        // ④ 成功したらリダイレクト
        return redirect('/mypage?tab=sell')->with('success', '商品が正常に登録されました。');
    }
}
