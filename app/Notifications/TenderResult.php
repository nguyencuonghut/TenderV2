<?php

namespace App\Notifications;

use App\Models\Bid;
use App\Models\Tender;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TenderResult extends Notification implements ShouldQueue
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
        $url = '/tenders/' . $this->tender_id;
        $tender = Tender::findOrFail($this->tender_id);
        $selected_bids = Bid::where('tender_id', $tender->id)->where('is_selected', true)->get();
        return (new MailMessage)
                    ->subject('Thư báo kết quả đấu thầu: ' . $tender->title)
                    ->line('Công ty CP dinh dưỡng Hồng Hà xin thông báo kết quả đấu thầu cho: ' . $tender->title)
                    ->action('Mở tender', url($url))
                    ->line('Xin cảm ơn!')
                    ->markdown('mail.tender.result', ['url' => url($url), 'tender' => $tender, 'selected_bids' => $selected_bids]);

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
