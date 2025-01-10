<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function open()
    {
        $user = User::with('profile')->find(Auth::id());

        return view('profile', compact('user'));
    }

    public function storeOrUpdate(AddressRequest $addressRequest, ProfileRequest $profileRequest)
    {
        // ログイン中のユーザーのプロフィールを取得
        $profile = Profile::where('user_id', Auth::id())->first();

        // すでにプロフィールが存在する場合、更新処理
        if ($profile) {
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

            return redirect()->route('mypage.open')->with('success', 'プロフィールが更新されました');
        }

        // プロフィールがまだ作成されていない場合は新規作成
        $addressData = $addressRequest->validated();
        $profileData = $profileRequest->validated();

        // 郵便番号にハイフンを自動挿入
        $addressData['postal_number'] = preg_replace(
            '/^(\d{3})(\d{4})$/',
            '$1-$2',
            str_replace('-', '', $addressData['postal_number']) // ハイフンを一度除去
        );

        if ($profileRequest->hasFile('profile_image')) {
            // ファイルの拡張子を取得
            $extension = $profileRequest->file('profile_image')->getClientOriginalExtension();

            // ユーザーIDをファイル名として使用（例: 1.jpg）
            $fileName = 'Profile' . Auth::id() . '_' . time() . '.' . $extension;

            // ファイルを保存し、保存先パスを取得
            $filePath = $profileRequest->file('profile_image')->storeAs('profile_images', $fileName, 'public');
            $fileName = 'storage/' . $filePath; // フルパスを生成
        } else {
            $fileName = null;
        }

        // プロフィールの保存
        Profile::create([
            'profile_name' => $addressData['profile_name'],
            'postal_number' => $addressData['postal_number'],
            'address' => $addressData['address'],
            'building' => $addressData['building'],
            'profile_image' => $fileName,
            'user_id' => Auth::id(),
        ]);

        return redirect('/')->with('success', 'プロフィールが作成されました');
    }
}
