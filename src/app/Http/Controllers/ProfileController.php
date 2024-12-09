<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Profile;

class ProfileController extends Controller
{
    public function open()
    {
        $user = User::with('profile')->find(Auth::id());

        return view('profile', compact('user'));
    }

    public function store(AddressRequest $addressRequest, ProfileRequest $profileRequest)
    {
        // 現在のユーザーがすでにプロフィールを持っているか確認
        $existingProfile = Profile::where('user_id', Auth::id())->first();

        if ($existingProfile) {
            // すでにプロフィールが存在する場合
            return redirect()->back()->with('error', 'プロフィールはすでに作成されています');
        }

        // バリデーション済みのデータを取得
        $addressData = $addressRequest->validated();
        $profileData = $profileRequest->validated();

        // プロフィール画像の保存処理
        if ($profileRequest->hasFile('profile_image')) {
            // ファイルの拡張子を取得
            $extension = $profileRequest->file('profile_image')->getClientOriginalExtension();

            // ユーザーIDをファイル名として使用（例: 1.jpg）
            $fileName = Auth::id() . '.' . $extension;

            // ファイルを保存
            $filePath = $profileRequest->file('profile_image')->storeAs('profile_images', $fileName, 'public');
        } else {
            $fileName = null;
        }

        // プロフィールの保存
        $profile = Profile::create([
            'profile_name' => $addressData['profile_name'],
            'postal_number' => $addressData['postal_number'],
            'address' => $addressData['address'],
            'building' => $addressData['building'],
            'profile_image' => $fileName,
            'user_id' => Auth::id(),
        ]);

        return redirect('/')->with('success', 'プロフィールを更新しました');
    }
}
