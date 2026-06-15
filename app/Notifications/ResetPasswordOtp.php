<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordOtp extends Notification
{
    use Queueable;

    public string $code;
    /**
     * Create a new notification instance.
     */
    public function __construct(string $code)
    {
        $this->code = $code;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Kode Pemulihan Sandi WARSA')
            ->greeting('Halo!')
            ->line('Anda menerima email ini karena kami menerima permintaan pemulihan kata sandi untuk akun Anda.')
            ->line('Kode Pemulihan Anda: **' . $this->code . '**')
            ->line('Silakan masukkan kode tersebut di aplikasi untuk membuat kata sandi baru.')
            ->line('Jika Anda tidak merasa meminta pemulihan kata sandi, abaikan email ini dan akun Anda akan tetap aman.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
