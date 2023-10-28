<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendNewRegisteredUserEmailNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
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
            ->subject('İmtihan Bekleme Listesindesiniz')
            ->line('Geleceğin Başarı Anahtarı İmtihan bekleyen listesine katıldığınız için teşekkür ederiz.')
            ->line('İmtihan, geleceğin eğitimini şekillendirmeye yardımcı olacak bir araç ve sizinle bu yolculuğa çıkmayı dört gözle bekliyoruz.')
            ->line("Şu an için, İmtihan'ı denemeye başlamak için bir davetiye beklemek durumundasınız. Ancak merak etmeyin, beklerken şunları yapabilirsiniz:")
            ->line("- İmtihan'ın resmi web sitesini ziyaret ederek projelerimiz, özelliklerimiz ve geliştirdiğimiz eğitim araçları hakkında daha fazla bilgi edinebilirsiniz.")
            ->line("- Eğitim teknolojileri dünyasındaki en son gelişmeleri ve İmtihan ile ilgili güncellemeleri öğrenmek için bizi sosyal medya platformlarında takip edebilirsiniz.")
            ->action('imtihan.tech', url('https://imtihan.tech'))
            ->line("İmtihan Ekibi 😉");
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
