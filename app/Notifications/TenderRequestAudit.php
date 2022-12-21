<?php

namespace App\Notifications;

use App\Models\Tender;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TenderRequestAudit extends Notification
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
        $url = '/admin/tenders/getAudit/' . $this->tender_id;
        $tender = Tender::findOrFail($this->tender_id);
        return (new MailMessage)
                    ->subject('Yêu cầu kiểm tra kết quả tender: ' . $tender->title)
                    ->line('Xin mời bạn kiểm tra kết quả thầu do phòng Mua đề xuất: ' . $tender->title)
                    ->action('Kiểm tra kết quả thầu', url($url))
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
