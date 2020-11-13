<?php

namespace Webkul\SAASCustomizer\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword;

class SuperAdminResetPassword extends ResetPassword
{
    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }
        
        return (new MailMessage)
            ->from("superadmin@bagsaas.com", "Super Admin Saas")
            ->view('saas::emails.super-admin.forget-password', [
                'user_name' => $notifiable->name,
                'token' => $this->token
            ]);
    }
}
