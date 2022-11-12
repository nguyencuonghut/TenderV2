<?php

namespace App\Notifications;

use App\Models\QuantityAndDeliveryTime;
use App\Models\Tender;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TenderInProgress extends Notification implements ShouldQueue
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
        $quantity_and_delivery_times = QuantityAndDeliveryTime::where('tender_id', $tender->id)->get();

        return (new MailMessage)
                    ->subject('Thư chào thầu: ' . $tender->title)
                    ->action('Mở tender', url($url))
                    ->markdown('mail.tender.inprogress', ['url' => url($url), 'tender' => $tender, 'quantity_and_delivery_times' => $quantity_and_delivery_times]);

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
