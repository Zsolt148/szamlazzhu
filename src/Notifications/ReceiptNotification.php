<?php

namespace Zsolt148\Szamlazzhu\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Zsolt148\Models\Applicant;
use Zsolt148\Szamlazzhu\Models\Receipt;

class ReceiptNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Applicant $applicant;

    public Receipt $receipt;

    /**
     * Create a new notification instance.
     */
    public function __construct(Applicant $applicant, Receipt $receipt)
    {
        $this->applicant = $applicant;
        $this->receipt = $receipt;
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
        $mail = (new MailMessage)
            ->subject('Bizonylat érkezett')
            ->greeting("Kedves {$this->applicant->name}!")
            ->line('Új bizonylat érkezett!');

        $mail->attach($this->receipt->receipt_file_path, [
            'as' => $this->receipt->receipt_file,
            'mime' => 'application/pdf',
        ]);

        return $mail;
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
