<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class CustomResetPasswordNotification extends ResetPassword
{
    public function toMail($notifiable)
    {
        $minutes = config('auth.passwords.' . config('auth.defaults.passwords') . '.expire');

        return (new MailMessage)
            ->subject('Solicitud de restablecimiento de contraseña')
            ->greeting('¡Hola!')
            ->line('Estás recibiendo este correo porque hemos recibido una solicitud para restablecer la contraseña de tu cuenta.')
            ->action('Restablecer Contraseña', url(route('password.reset', [
                'token' => $this->token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false)))
            ->line("Este enlace para restablecer la contraseña caducará en {$minutes} minutos.")
            ->line('Si no solicitaste un restablecimiento de contraseña, no es necesario realizar ninguna acción.')
            ->salutation('Saludos, ' . config('app.name'));
    }
}
