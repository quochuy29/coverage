<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $mailInfo;
    public $mailTo = [];
    public $subject;
    public $mailBcc = [];
    public $mailCc = [];
    public $view;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $mailInfo = [])
    {
        $this->mailInfo = $mailInfo;
        $this->subject = $mailInfo['subject'] ?? '';
        $this->view = $mailInfo['view'] ?? '';
        $this->mailTo = $mailInfo['mailTo'] ?? [];
        $this->mailBcc = $mailInfo['mailBcc'] ?? [];
        $this->mailCc = $mailInfo['mailCc'] ?? [];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)
            ->to($this->mailTo)
            ->cc($this->mailCc)
            ->bcc($this->mailBcc)
            ->view($this->view);
    }
}
