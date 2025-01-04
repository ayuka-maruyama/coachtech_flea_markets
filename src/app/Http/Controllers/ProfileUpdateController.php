<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\ProfileRequest;

class ProfileUpdateController extends Controller
{
    public function updateOpen()
    {
        $user = User::with('profile')->find(Auth::id());

        return view('profile-update', compact('user'));
    }

    public function updateStore(AddressRequest $addressRequest, ProfileRequest $profileRequest)
    {
        // ログイン中のユーザーのプロフィールを取得
        $profile = Profile::where('user_id', Auth::id())->first();

        // 画像ファイルの処理
        if ($profileRequest->hasFile('profile_image')) {
            // 新しい画像がアップロードされている場合、既存画像を削除
            if ($profile->profile_image) {
                Storage::delete('public/' . $profile->profile_image); // 既存の画像を削除
            }

            // 新しい画像を保存
            $extension = $profileRequest->file('profile_image')->getClientOriginalExtension();
            $fileName = 'Profile' . Auth::id() . '_' . time() . '.' . $extension;
            $filePath = $profileRequest->file('profile_image')->storeAs('profile_images', $fileName, 'public');

            // 新しい画像のパスを保存
            $profile->profile_image = 'profile_images/' . $fileName;
        }

        // プロフィールの更新
        $profile->update([
            'profile_name' => $addressRequest->profile_name,
            'postal_number' => $addressRequest->postal_number,
            'address' => $addressRequest->address,
            'building' => $addressRequest->building,
        ]);

        // 更新が成功したらリダイレクト
        return redirect()->route('mypage.open')->with('success', 'プロフィールが更新されました');
    }
}
