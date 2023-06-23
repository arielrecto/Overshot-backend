<?php

namespace App\Actions\Profile;

use App\Models\Profile;
use App\Models\User;

class ShowProfileAction{

    // protected $profile;
    // protected $avatar;

    public function handle($id){

        $user = User::find($id);
        $profile = $user->profile()->with('avatar');

        if($profile == null){
            return abort(404, 'Profile Not Found');
        }

        // $this->profile = $profile;
        // $this->avatar = $profile->avatar;
        return $profile;
    }
    // public function toArray(){
    //     return [
    //         'profle' => $this->profile,
    //         'avatar' => $this->avatar
    //     ];
    // }
}
