<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\api\ResponseController;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Http\Controllers\Api\MailController;


class AuthController extends ResponseController {

    public function register( RegisterRequest $request ) {

        $request->validated();

        $user = User::create([
            "name" => $request[ "name" ],
            "email" => $request[ "email" ],
            "password" => bcrypt( $request[ "password" ])
        ]);


        return $this->sendResponse( $user, "Sikeres regisztráció" );
    }

    public function login(LoginRequest $request) {
        $request->validated();
    
        if (Auth::attempt(["email" => $request["email"], "password" => $request["password"]])) {
            $user = Auth::user();
    
            $banningTime = (new BannerController)->getBanningTime($user->email);
    
            if ($banningTime != null && Carbon::now() < $banningTime) {
                $errorMessage = [
                    "Következő lehetőség:",
                    $banningTime
                ];
                return $this->sendError("Azonosítási hiba", $errorMessage, 405);
            } else {
                (new BannerController)->resetLoginCounter($user->email);
                (new BannerController)->resetBanningTime($user->email);
    
                $token = $user->createToken($user->name . "Token")->plainTextToken;
    
                $data = [
                    "name" => $user->name,
                    "token" => $token
                ];
    
                return $this->sendResponse($data, "Sikeres bejelentkezés");
            }
        } else {
            (new BannerController)->setLoginCounter($request["email"]);
            $counter = (new BannerController)->getLoginCounter($request["email"]);
    
            if ($counter > 3) {
                (new BannerController)->setBanningTime($request["email"]);
                // $time = Carbon::now();
                // ( new MailController )->sendMail( $request[ "email" ], $time );
                return $this->sendError("Azonosítási hiba", "Hibás email vagy jelszó (le lett tiltva egy időre)", 401);
            }
    
            return $this->sendError("Azonosítási hiba", "Hibás email vagy jelszó", 401);
        }
    }
    public function logout() {

        $user = auth( "sanctum" )->user();
        $user->currentAccessToken()->delete();

        return $this->sendResponse( $user->name, "Sikeres kijelentkezés" );
    }

    public function getUsers() {

        $users = User::all();
        return $users;
    }
}
