<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;

class BannerController extends Controller
{
    public function getLoginCounter($email) {
        $user = User::where("email", $email)->first();
        return $user?->login_counter ?? 0;
    }
    
    public function setLoginCounter($email) {
        $user = User::where("email", $email)->first();
        if ($user) {
            $user->increment("login_counter");
        }
    }
    
    public function resetLoginCounter($email) {
        $user = User::where("email", $email)->first();
        if ($user) {
            $user->login_counter = 0;
            $user->save();
        }
    }
    
    public function getBanningTime($email) {
        $user = User::where("email", $email)->first();
        return $user?->banning_time;
    }
    
    public function setBanningTime($email) {
        $user = User::where("email", $email)->first();
        if ($user) {
            $user->banning_time = Carbon::now()->addMinutes(1);
            $user->save();
        }
    }
    
    public function resetBanningTime($email) {
        $user = User::where("email", $email)->first();
        if ($user) {
            $user->banning_time = null;
            $user->save();
        }
    }
}