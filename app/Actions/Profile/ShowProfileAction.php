<?php

namespace App\Actions\Profile;

use App\Models\Profile;

class ShowProfileAction{

    protected $profile;
    protected $avatar;
 
    public function handle($id){

        $profile = Profile::find($id);

        if($profile == null){
            return abort(404, 'Profile Not Found');
        }

        $this->profile = $profile;
        $this->avatar = $profile->avatar;
        return $profile;
    }
    public function toArray(){
        return [
            'profle' => $this->profile,
            'avatar' => $this->avatar
        ];
    }
}