<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class CustomResetPassword extends BaseResetPassword
{
    public function toMail($notifiable)
    {
        $url = url(config('app.url'). route('password.reset', ['token'=>$this->token], false));
        return (new MailMessage)
            ->subject('Vérifiez votre adresse e-mail')
            ->view('emails.custom-reset-password', ['url' => $url]);
    }
}
