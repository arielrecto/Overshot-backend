<?php

namespace App\Http\Services;


use App\Models\User;
use Illuminate\Support\Str;
use App\Actions\Role\AssignRoleUser;
use App\Models\Avatar;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthService
{
    public function register($request)
    {

        // $request->validate([
        //     'name' => 'required',
        //     'email' => 'required',
        //     'password' => 'required|confirmed',
        // ]);


        $user = User::create([
            'name' => $request->name,
            'slug_name' => Str::slug($request->name),
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);


        // $profile = Profile::create([
        //     'last_name' => $request->profile['lastName'],
        //     'first_name' => $request->profile['firstName'],
        //     'middle_name' => $request->profile['middleName'],
        //     'gender' => $request->profile['gender'],
        //     'age' => $request->profile['age'],
        //     'street_no' => $request->profile['street'],
        //     'village' => $request->profile['village'],
        //     'municipality' => $request->profile['municipality'],
        //     'region' => $request->profile['region'],
        //     'zip_code' => $request->profile['zipCode'],
        //     'phone_no' => $request->profile['phoneNo'],
        //     'tel_no' => $request->profile['telNo'],
        //     'user_id' => $user->id
        //  ]);



        //  $image = $request->avatar;

        //  $image = $request->image;  // your base64 encoded
        //  $image = str_replace('data:image/png;base64,', '', $image);
        //  $image = str_replace(' ', '+', $image);
        //  $imageName =  'Img' . now() .'.'.'png';
        //  $filename = preg_replace('~[\\\\\s+/:*?"<>|+-]~', '-', $imageName);

        //  $imageDecoded = base64_decode($image);

        //  Avatar::create([
        //      'name' => $imageName,
        //      'image_url' => asset('storage/product/image/' . $filename),
        //      'profile_id' => $profile->id
        //  ]);
        //  Storage::disk('public')->put('avatars/' . $filename, $imageDecoded);


        $userAssignRole = new AssignRoleUser();
        $role = $userAssignRole->handle($user, 'client', 'web');

        return response([
            'user' => $user,
            'role' => $role
        ]);
    }
    public function logout($request)
    {
        $response = $request->user()->tokens()->delete();

        if (!$response) {
            return abort(500);
        }

        return response([
            'message' => 'Logout Successfully'
        ], 200);
    }
    public function login($request)
    {
        if (!Auth::attempt($request->only(['email', 'password']))) {
            return abort(401, 'Incorrect Credentials');
        }
        $user = User::where('email', $request->email)->first();

        return response([
            'user' => $user,
            'message' => 'Login Successfully',
            'token' => $user->createToken($user->name)->plainTextToken,
            'role' => $user->getRoleNames()
        ], 200);
    }
}
