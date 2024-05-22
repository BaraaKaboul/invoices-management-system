<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class AddInvoice extends Notification
{
    use Queueable;

    private $invoice;
    public function __construct($invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
//    public function toMail(object $notifiable): MailMessage
//    {
//        $url = 'http://127.0.0.1:8000/invoicesDetails/'.$this->invoice_id;
//        return (new MailMessage)
//            ->subject('فاتورة بنك')
//            ->line('The introduction to the notification.')// هون العبارة يلي رح تطلع بالايميل فوق
//            ->action('Notification Action', $url)//هون مكان ما رح يساوي button بالايميل والرسالة الي عليها هي هاي العبارة Notification Action
//            ->line('Thank you for using our application!');// هون العبارة يلي رح تطلع بالايميل تحت
//    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            // من هون فينا نخزن مباشرة جوات حقل data بالجدول تبع النوتيفيكيشنز
            'id'=>$this->invoice->id,
            'title'=>'تم إضافة الفاتورة بواسطة: ',
            'user'=>Auth::user()->name,
        ];
    }
}
