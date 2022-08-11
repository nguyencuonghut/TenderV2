<?php

namespace App\Notifications;

use App\Models\Tender;
use App\Models\TenderApproveComment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TenderApproved extends Notification implements ShouldQueue
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
        $url = '/admin/tenders/' . $this->tender_id;
        $tender = Tender::findOrFail($this->tender_id);
        if('Đồng ý' == $tender->approve_result){
            $subject = 'Kết quả tender ' . '('. $tender->title . ' )' . ' đã được Đồng Ý.';
        }else{
            $subject = 'Kết quả tender ' . '('.  $tender->title . ' )' . ' đã bị Từ Chối.';
        }

        $comment = TenderApproveComment::where('tender_id', $tender->id)->latest()->first();
        if($comment->comment){
            return (new MailMessage)
                        ->subject($subject)
                        ->line('Ban lãnh đạo đã phê duyệt xong kết quả cho tender: ' . $tender->title)
                        ->line('Bình luận:')
                        ->line($comment->comment)
                        ->action('Xem kết quả', url($url))
                        ->line('Xin cảm ơn!');
        }else{
            return (new MailMessage)
            ->subject($subject)
            ->line('Ban lãnh đạo đã phê duyệt xong kết quả cho tender: ' . $tender->title)
            ->action('Xem kết quả', url($url))
            ->line('Xin cảm ơn!');
        }
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
