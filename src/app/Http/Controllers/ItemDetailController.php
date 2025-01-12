<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Favorite;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class ItemDetailController extends Controller
{
    public function showItemDetailForm($item_id)
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

    public function sentComment(CommentRequest $request, $item_id)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login.form.show');
        }

        if (!$user->profile) {
            return redirect()->route('profile.open')->with('message', 'プロフィールを設定してください');
        }

        $comment = Comment::create([
            'user_id' => Auth::id(),
            'item_id' => $item_id,
            'comment' => $request->comment,
        ]);

        return redirect()->route('detail.form.show', ['item_id' => $item_id])
            ->with('success', 'コメントを投稿しました。');
    }

    public function toggleFavorite($item_id)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'ログインが必要です。'], 401);
        }

        $favorite = Favorite::where('user_id', $user->user_id)->where('item_id', $item_id)->first();

        if ($favorite) {
            $favorite->delete();
            $isFavorited = false;
        } else {
            Favorite::create([
                'user_id' => $user->user_id,
                'item_id' => $item_id,
            ]);
            $isFavorited = true;
        }

        $favoriteCount = Favorite::where('item_id', $item_id)->count();

        return response()->json([
            'isFavorited' => $isFavorited,
            'favoriteCount' => $favoriteCount,
        ]);
    }
}
