<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{

    private $authUser;

    public function __construct()
    {
        $this->authUser = Auth::user();
    }

    public function getCurrentLocation(){
        dd($this->authUser->id);
    }
    public function updateCurrentLocation(string $id){

    }
    public function storeCurrentLocation(Request $request){


        return $request->all();


        $location = Location::create([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'address' => $request->address,
            'user_id' => $this->authUser->id
        ]);
    }
}
