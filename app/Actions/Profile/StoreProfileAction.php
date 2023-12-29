<?php

namespace App\Actions\Profile;

use App\Models\Avatar;
use App\Models\Profile;
use Illuminate\Http\Request;
use App\Actions\ImageUploader;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StoreProfileAction
{
    public function handle(Request $request)
    {

        $profile = Profile::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'middle_name' => $request->middle_name,
            'age' => $request->age,
            'gender' => $request->gender,
            'street_no' => $request->street_no,
            'village' => $request->village,
            'municipality' => $request->municipality,
            'region' => $request->region,
            'zip_code' => $request->zip_code,
            'phone_no' => $request->phone_no,
            'tel_no' => $request->tel_no,
            'user_id' => Auth::user()->id
        ]);

        if ($request->image !== null) {

            $image = $request->image;  // your base64 encoded
            $image = str_replace('data:image/jpeg;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName =  'prfl' . now() . '.' . 'png';
            $filename = preg_replace('~[\\\\\s+/:*?"<>|+-]~', '-', $imageName);

            $imageDecoded = base64_decode($image);

            Storage::disk('public')->put('avatar/' . $filename, $imageDecoded);

            Avatar::create([
                'name' => $filename,
                'image_url' => asset('storage/avatar/' . $filename),
                'profile_id' => $profile->id
            ]);
        }

        return $profile;
    }
}
