<?php

namespace App\Notifications;

use App\Models\Admin;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminCreated extends Notification implements ShouldQueue
{
    use Queueable;
    protected $admin_id;
    protected $password;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($admin_id, $password)
    {
        $this->admin_id = $admin_id;
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
        $url = '/admin/';
        $admin = Admin::findOrFail($this->admin_id);
        return (new MailMessage)
                    ->subject('Kích hoạt tài khoản Admin phần mềm Tender Honghafeed ' . $admin->name)
                    ->line('Xin mời kích hoạt tài khoản: ' . $admin->name .  ' (' . $admin->email . ')')
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
