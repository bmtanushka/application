<?php

/** ---------------------------------------------------------------------------------------------------
 * Daily Client Digest Email Cron - FINAL VERSION
 * Sends a daily digest email to clients containing all pending notifications from the past 24 hours
 * This version properly cleans notification content to avoid CSS/HTML issues
 * @package    Grow CRM
 * @author     NextLoop
 *-----------------------------------------------------------------------------------------------------*/

namespace App\Cronjobs;

use App\Models\EmailQueue;
use App\Models\User;
use App\Mail\ClientDailyDigest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class ClientDailyDigestCron
{
    public function __invoke()
    {
        //[MT] - tenants only
        if (env('MT_TPYE')) {
            if (\Spatie\Multitenancy\Models\Tenant::current() == null) {
                return;
            }
            //boot system settings
            middlwareBootSystem();
            middlewareBootMail();
        }

        // Check if client digest emails are enabled
        if (config('system.settings_clients_daily_digest') != 'enabled') {
            return;
        }

        // Get all unique client email addresses that have pending digest emails
        $clientEmails = EmailQueue::where('emailqueue_delivery_type', 'digest')
            ->where('emailqueue_status', 'new')
            ->select('emailqueue_to')
            ->groupBy('emailqueue_to')
            ->get();

        if ($clientEmails->isEmpty()) {
            return;
        }

        foreach ($clientEmails as $emailRecord) {
            $clientEmail = $emailRecord->emailqueue_to;

            // Get all digest emails for this client
            $digestEmails = EmailQueue::where('emailqueue_delivery_type', 'digest')
                ->where('emailqueue_status', 'new')
                ->where('emailqueue_to', $clientEmail)
                ->orderBy('emailqueue_created', 'desc')
                ->get();

            if ($digestEmails->isEmpty()) {
                continue;
            }

            // Get the client user
            $client = User::where('email', $clientEmail)->where('type', 'client')->first();

            if (!$client) {
                // If client not found, delete the orphaned emails
                \Log::warning("Client not found for email: {$clientEmail}. Deleting orphaned digest emails.");
                EmailQueue::where('emailqueue_delivery_type', 'digest')
                    ->where('emailqueue_status', 'new')
                    ->where('emailqueue_to', $clientEmail)
                    ->delete();
                continue;
            }

            // Mark all emails in the batch as processing
            foreach ($digestEmails as $email) {
                $email->update([
                    'emailqueue_status' => 'processing',
                    'emailqueue_started_at' => now(),
                ]);
            }

            // Group notifications by type/category
            $groupedNotifications = $this->groupNotifications($digestEmails);

            // Send the digest email
            try {
                // Create the mail instance
                $digestMail = new ClientDailyDigest($client, $groupedNotifications, $digestEmails);
                
                // Send the email
                Mail::to($clientEmail)->send($digestMail);

                // Get the rendered email content for logging
                $renderedSubject = 'Daily Activity Summary from ' . config('system.settings_company_name', 'Your Team') . ' (' . $digestEmails->count() . ' updates)';
                
                // Build a simple log body
                $logBody = $this->buildLogBody($groupedNotifications, $digestEmails->count());

                // Log the digest email
                $log = new \App\Models\EmailLog();
                $log->emaillog_email = $clientEmail;
                $log->emaillog_subject = $renderedSubject;
                $log->emaillog_body = $logBody;
                $log->save();

                // Delete all processed digest emails for this client
                EmailQueue::where('emailqueue_delivery_type', 'digest')
                    ->where('emailqueue_to', $clientEmail)
                    ->delete();

                \Log::info("Successfully sent digest email to {$clientEmail} with {$digestEmails->count()} notifications");

            } catch (\Exception $e) {
                // Log error and mark emails as failed
                \Log::error("Failed to send digest email to {$clientEmail}: " . $e->getMessage());
                \Log::error($e->getTraceAsString());
                
                // Reset status back to new for retry
                EmailQueue::where('emailqueue_delivery_type', 'digest')
                    ->where('emailqueue_status', 'processing')
                    ->where('emailqueue_to', $clientEmail)
                    ->update(['emailqueue_status' => 'new']);
            }
        }

        // Reset last cron run data
        \App\Models\Settings::where('settings_id', 1)
            ->update([
                'settings_cronjob_has_run' => 'yes',
                'settings_cronjob_last_run' => now(),
            ]);
    }

    /**
     * Group notifications by category for better digest presentation
     */
    private function groupNotifications($digestEmails)
    {
        $grouped = [
            'tasks' => [],
            'projects' => [],
            'tickets' => [],
            'other' => [],
        ];

        foreach ($digestEmails as $email) {
            $category = 'other';
            
            // Determine category based on resource type or subject
            if ($email->emailqueue_resourcetype == 'task' || stripos($email->emailqueue_subject, 'task') !== false) {
                $category = 'tasks';
            } elseif ($email->emailqueue_resourcetype == 'project' || stripos($email->emailqueue_subject, 'project') !== false) {
                $category = 'projects';
            } elseif ($email->emailqueue_resourcetype == 'ticket' || stripos($email->emailqueue_subject, 'ticket') !== false) {
                $category = 'tickets';
            }

            $grouped[$category][] = [
                'subject' => $email->emailqueue_subject,
                'message' => $this->cleanEmailContent($email->emailqueue_message),  // Clean the content
                'created_at' => $email->emailqueue_created,
                'resource_type' => $email->emailqueue_resourcetype,
                'resource_id' => $email->emailqueue_resourceid,
            ];
        }

        // Remove empty categories
        return array_filter($grouped, function ($items) {
            return !empty($items);
        });
    }

    /**
     * Clean email content by extracting only the main message
     * Removes styles, scripts, and extra HTML
     */
    private function cleanEmailContent($htmlContent)
    {
        // Remove style tags and their content
        $htmlContent = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', $htmlContent);
        
        // Remove script tags and their content
        $htmlContent = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $htmlContent);
        
        // Remove @media queries that might be outside style tags
        $htmlContent = preg_replace('/@media[^{]+\{(([^{}]+|\{[^{}]*\})*)\}/is', '', $htmlContent);
        
        // Remove @font-face declarations
        $htmlContent = preg_replace('/@font-face[^{]+\{[^}]+\}/is', '', $htmlContent);
        
        // Remove any remaining curly braces blocks (leftover CSS)
        $htmlContent = preg_replace('/\{[^}]*\}/is', '', $htmlContent);
        
        // Try to extract just the main content
        // Look for content in td-1 class (common in your email templates)
        if (preg_match('/<td class=["\']td-1["\'][^>]*>(.*?)<\/td>/is', $htmlContent, $matches)) {
            $htmlContent = $matches[1];
        }
        
        // Remove excessive whitespace and newlines
        $htmlContent = preg_replace('/\s+/', ' ', $htmlContent);
        
        // Remove any stray CSS property-like patterns (color:, font-family:, etc.)
        $htmlContent = preg_replace('/[\w-]+\s*:\s*[^;]+;/', '', $htmlContent);
        
        // Keep only basic HTML tags (p, br, strong, em, a, ul, li)
        $htmlContent = strip_tags($htmlContent, '<p><br><strong><b><em><i><a><ul><li><div>');
        
        // Trim and clean up multiple spaces
        $htmlContent = trim($htmlContent);
        $htmlContent = preg_replace('/\s{2,}/', ' ', $htmlContent);
        
        // If content is still too messy or empty, provide a fallback
        if (empty($htmlContent) || strlen($htmlContent) < 10) {
            $htmlContent = '<p>Notification received</p>';
        }
        
        return $htmlContent;
    }

    /**
     * Build a simple text body for email log
     */
    private function buildLogBody($groupedNotifications, $totalCount)
    {
        $body = "Daily Digest Email Summary\n";
        $body .= "Total Notifications: {$totalCount}\n\n";
        
        foreach ($groupedNotifications as $category => $notifications) {
            $body .= strtoupper($category) . " (" . count($notifications) . "):\n";
            foreach ($notifications as $notification) {
                $body .= "- " . strip_tags($notification['subject']) . "\n";
            }
            $body .= "\n";
        }
        
        return $body;
    }
}