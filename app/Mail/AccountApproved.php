<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AccountApproved extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $title;
    public $fname;
    public $lname;
    public $email;
    public $pass;
    public function __construct($title,$fname,$lname,$email,$pass)
    {
        $this->title = $title;
        $this->fname = $fname;
        $this->lname = $lname;
        $this->email = $email;
        $this->pass = $pass;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.approved');
    }
}
