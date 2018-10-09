<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class New_Member extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $title, $fname, $lname, $role, $career;
    public function __construct($title, $fname, $lname, $role, $career)
    {
        //
        $this->title = $title;
        $this->fname = $fname;
        $this->lname = $lname;
        $this->role = $role;
        $this->career = $career;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.newmember');
    }
}
