<?php

namespace App\Notifications;

use App\Models\Tender;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReminderTenderInProgress extends Notification implements ShouldQueue
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
        $url = url('tenders/' . $this->tender_id);
        $tender = Tender::findOrFail($this->tender_id);
        return (new MailMessage)
                    ->subject('!!! Nhắc nhở !!! Thời gian chào thầu: ' . $tender->title)
                    ->line('Chỉ còn 15 phút nữa là đến giờ mở thầu. Xin mời quý nhà cung cấp chào thầu cho: ' . $tender->title . '.')
                    ->line('Thời gian mở thầu: ' . date('d/m/Y H:i', strtotime($tender->tender_start_time)) . ' - '. date('d/m/Y H:i', strtotime($tender->tender_end_time)) . '.')
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
