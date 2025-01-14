<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Requests\ProfileRequest;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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
        $profile = Profile::where('user_id', Auth::id())->first();

        if ($profile) {
            if ($profileRequest->hasFile('profile_image')) {
                if ($profile->profile_image) {
                    Storage::delete('public/' . $profile->profile_image);
                }

                $extension = $profileRequest->file('profile_image')->getClientOriginalExtension();
                $fileName = 'Profile' . Auth::id() . '.' . $extension;
                $filePath = $profileRequest->file('profile_image')->storeAs('profile_images', $fileName, 'public');

                $profile->profile_image = 'storage/profile_images/' . $fileName;
            }

            $profile->update([
                'profile_name' => $addressRequest->profile_name,
                'postal_number' => $addressRequest->postal_number,
                'address' => $addressRequest->address,
                'building' => $addressRequest->building,
            ]);

            return redirect()->route('mypage.form.show')->with('success', 'プロフィールが更新されました');
        }

        $addressData = $addressRequest->validated();
        $profileData = $profileRequest->validated();

        $addressData['postal_number'] = preg_replace(
            '/^(\d{3})(\d{4})$/',
            '$1-$2',
            str_replace('-', '', $addressData['postal_number'])
        );

        if ($profileRequest->hasFile('profile_image')) {
            $extension = $profileRequest->file('profile_image')->getClientOriginalExtension();

            $fileName = 'Profile' . Auth::id() . '_' . time() . '.' . $extension;

            $filePath = $profileRequest->file('profile_image')->storeAs('profile_images', $fileName, 'public');
            $fileName = 'storage/' . $filePath;
        } else {
            $fileName = null;
        }

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
