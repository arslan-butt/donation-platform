<?php

namespace App\Notifications;

use App\Models\Donation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DonationConfirmation extends Notification implements ShouldQueue
{
    use Queueable;

    protected Donation $donation;

    /**
     * Create a new notification instance.
     */
    public function __construct(Donation $donation)
    {
        $this->donation = $donation;

        $this->donation->load('campaign');
    }

    public function getDonation()
    {
        return $this->donation;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
        // you could also add 'database' here if you want to store it
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $campaign = $this->donation->campaign;

        return (new MailMessage)
            ->subject('Thank you for your donation!')
            ->greeting("Hello {$notifiable->name},")
            ->line("We’ve received your donation of **\${$this->donation->amount}** to the “{$campaign->title}” campaign.")
            ->action('View Campaign', url("/campaigns/{$campaign->id}"))
            ->line('Your support means the world to us!')
            ->salutation('— The ACME Donation Team');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $campaign = $this->donation->campaign;

        return [
            'donation_id' => $this->donation->id,
            'campaign_id' => $campaign->id,
            'campaign_name' => $campaign->title,
            'amount' => $this->donation->amount,
            'donated_at' => $this->donation->donated_at?->toDateTimeString(),
        ];
    }
}
