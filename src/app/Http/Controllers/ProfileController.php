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
