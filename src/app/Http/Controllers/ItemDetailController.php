<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Favorite;
use App\Models\Comment;

class ItemDetailController extends Controller
{
    public function open($item_id)
    {
        $item = Item::findOrFail($item_id);

        $comments = Comment::with(['user.profile'])->where('item_id', $item_id)->get();

        $favoriteCount = Favorite::where('item_id', $item_id)->count();
        $commentCount = $comments->count();

        return view('item-detail', compact('item', 'favoriteCount', 'commentCount', 'comments'));
    }

    public function comment(CommentRequest $request, $item_id)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login.open');
        }
        // commentsへデータを登録する
        // 登録後、現在の画面へリダイレクトし、コメント数が増加することを確認する

        $comment = Comment::create([
            'user_id' => Auth::id(),
            'item_id' => $item_id,
            'comment' => $request->comment,
        ]);

        return redirect()->route('detail.open', ['item_id' => $item_id])
            ->with('success', 'コメントを投稿しました。');
    }
}
