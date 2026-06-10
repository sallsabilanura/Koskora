<?php

namespace App\Mail;

use App\Models\Announcement;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AnnouncementMail extends Mailable
{
    use Queueable, SerializesModels;

    public $announcement;
    public $recipientName;

    /**
     * Create a new message instance.
     */
    public function __construct(Announcement $announcement, $recipientName)
    {
        $this->announcement = $announcement;
        $this->recipientName = $recipientName;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Pengumuman Baru: ' . $this->announcement->title)
                    ->view('emails.announcement');
    }
}
