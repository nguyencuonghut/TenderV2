<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserCreated extends Notification implements ShouldQueue
{
    use Queueable;
    protected $user_id;
    protected $password;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user_id, $password)
    {
        $this->user_id = $user_id;
        $this->password = $password;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = '/';
        $user = User::findOrFail($this->user_id);
        return (new MailMessage)
                    ->subject('Kích hoạt tài khoản phần mềm Tender Honghafeed ' . $user->name)
                    ->line('Xin mời kích hoạt tài khoản: ' . $user->name .  ' (' . $user->email . ')')
                    ->line('Mật khẩu: ' . $this->password)
                    ->action('Đăng nhập tài khoản của bạn', url($url))
                    ->line('Xin cảm ơn!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
