<?php

namespace App\Actions\Profile;

use App\Actions\ImageUploader;
use App\Models\Avatar;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        if ($request->hasFile('avatar')) {
            $uploader = new ImageUploader();
            $image = $request->file('avatar');
            $name = 'Img' . now() . '.' . $image->getClientOriginalExtension();
            $filename = preg_replace('~[\\\\\s+/:*?"<>|+-]~', '-', $name);
            $destination = 'public/avatar';
            $uploader->upload($image, $destination, $filename);

            Avatar::create([
                'name' => $filename,
                'image_url' => asset('storage/avatar/' . $filename),
                'profile_id' => $profile->id
            ]);
        }

        return $profile;
    }
}
