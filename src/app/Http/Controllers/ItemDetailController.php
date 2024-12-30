<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use Illuminate\Http\Request;
use App\Models\CategoryItem;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Favorite;
use App\Models\Comment;
use App\Models\Order;


class ItemDetailController extends Controller
{
    public function open($item_id)
    {
        $item = Item::with('categories')->findOrFail($item_id);

        $user = Auth::user();
        $userId = null;
        $isFavorited = false;
        $isPurchased = false;

        if ($user) {
            $userId = $user->user_id;
            $isFavorited = Favorite::where('user_id', $userId)
                ->where('item_id', $item_id)
                ->exists();

            $isPurchased = $item->stock_status === 1;
        }

        $favoriteCount = Favorite::where('item_id', $item_id)->count();
        $comments = Comment::with(['user.profile'])->where('item_id', $item_id)->get();
        $commentCount = $comments->count();

        return view('item-detail', compact(
            'userId',
            'item',
            'favoriteCount',
            'isFavorited',
            'comments',
            'commentCount',
            'isPurchased'
        ));
    }


    public function comment(CommentRequest $request, $item_id)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login.open');
        }

        $comment = Comment::create([
            'user_id' => Auth::id(),
            'item_id' => $item_id,
            'comment' => $request->comment,
        ]);

        return redirect()->route('detail.open', ['item_id' => $item_id])
            ->with('success', 'コメントを投稿しました。');
    }

    public function toggle($item_id)
    {
        // ログインしているユーザーを取得
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'ログインが必要です。'], 401);
        }

        // お気に入りのトグル処理
        $favorite = Favorite::where('user_id', $user->user_id)->where('item_id', $item_id)->first();

        if ($favorite) {
            // 既にお気に入り登録がある場合、削除
            $favorite->delete();
            $isFavorited = false;
        } else {
            // お気に入りに追加
            Favorite::create([
                'user_id' => $user->user_id,
                'item_id' => $item_id,
            ]);
            $isFavorited = true;
        }

        // お気に入りの件数を取得
        $favoriteCount = Favorite::where('item_id', $item_id)->count();

        // トグル結果をJSONで返す
        return response()->json([
            'isFavorited' => $isFavorited,
            'favoriteCount' => $favoriteCount,
        ]);
    }
}
