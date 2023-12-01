<?php

namespace Zsolt148\Szamlazzhu\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Zsolt148\Models\Applicant;
use Zsolt148\Szamlazzhu\Models\Invoice;

class InvoiceNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Applicant $applicant;

    public Invoice $invoice;

    /**
     * Create a new notification instance.
     */
    public function __construct(Applicant $applicant, Invoice $invoice)
    {
        $this->applicant = $applicant;
        $this->invoice = $invoice;
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
            ->subject('Számlád érkezett')
            ->greeting("Kedves {$this->applicant->name}!")
            ->line('Új számlád érkezett!');

        $mail->attach($this->invoice->invoice_file_path, [
            'as' => $this->invoice->invoice_file,
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
