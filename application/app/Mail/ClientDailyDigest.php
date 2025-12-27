<?php

/** --------------------------------------------------------------------------------
 * This class renders the [daily digest] email for clients
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;

class ClientDailyDigest extends Mailable
{
    use Queueable;

    /**
     * The client user
     */
    public $client;

    /**
     * Grouped notifications
     */
    public $groupedNotifications;

    /**
     * All digest emails
     */
    public $digestEmails;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($client, $groupedNotifications, $digestEmails)
    {
        $this->client = $client;
        $this->groupedNotifications = $groupedNotifications;
        $this->digestEmails = $digestEmails;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $companyName = config('system.settings_company_name', 'Your Team');
        $notificationCount = $this->digestEmails->count();
        
        $subject = "Daily Activity Summary from {$companyName} ({$notificationCount} updates)";

        return $this->subject($subject)
                    ->view('emails.client_daily_digest')
                    ->with([
                        'client' => $this->client,
                        'groupedNotifications' => $this->groupedNotifications,
                        'notificationCount' => $notificationCount,
                        'companyName' => $companyName,
                        'date' => now()->format('F j, Y'),
                    ]);
    }
}