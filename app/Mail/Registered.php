<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Registered extends Mailable
{
    use Queueable, SerializesModels;

    public $title, $fname, $lname, $email, $password ;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($title, $fname, $lname, $email, $password)
    {
        $this->title = $title;
        $this->fname = $fname;
        $this->lname = $lname;
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.registered');
    }
}
