<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Mail\AllertMail;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;
use Illuminate\Http\Request;

class MailController extends Controller
{
    public function sendMail($user, $time)
    {
        $content = [
            "title" => "Figyelmeztetés",
            "user" => $user,
            "time" => $time
        ];

        Mail::to('pikkypad@gmail.com')->send(new AllertMail($content));
    }
    public function sendWelcomeMail($email, $name)
    {
        Mail::to($email)->send(new WelcomeMail($name));
    }
}
