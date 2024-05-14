<?php

namespace App\Actions\Fortify;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Laravel\Fortify\Contracts\SendsTwoFactorAuthenticationCodes;

class SendTwoFactorAuthenticationCode implements SendsTwoFactorAuthenticationCodes
{
    public function send(array $user, string $code)
    {
        Mail::to($user['email'])->send(new TwoFactorAuthenticationNotification($code));
    }
}