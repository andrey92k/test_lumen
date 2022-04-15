<?php

namespace App\Traits;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

trait SenderService
{
    protected function generateToken($count)
    {
        return Str::random($count);
    }

    protected function sendTokenEmail($data)
    {
        $data['url'] = route('recover_password_link') . '?token=' . $data['token'] . '&email=' . $data['email'];

        Mail::send('emails.sendmail', $data, function ($message) use ($data) {
            $message->to($data['email'])
                ->subject('Recover');
            $message->from($data['email'], 'Recover');
        });
    }
}
