<?php

namespace App\Notifications;

use App\Models\Tender;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BidCreatedOrUpdated extends Notification implements ShouldQueue
{
    use Queueable;
    protected $tender_id;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($tender_id)
    {
        $this->tender_id = $tender_id;
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
        $url = '/bids/' . $this->tender_id . '/index';
        $tender = Tender::findOrFail($this->tender_id);
        return (new MailMessage)
                    ->subject('Có một nhà thầu gửi hoặc sửa chào giá cho tender: ' . $tender->title)
                    ->line('Một nhà thầu vừa gửi hoặc sửa chào giá cho tender: ' . $tender->title)
                    ->line('Bạn hãy vào xem để biết được xếp hạng chào giá của mình và điều chỉnh chào giá nếu cần!')
                    ->action('Mở tender', url($url))
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
