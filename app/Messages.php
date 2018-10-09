<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    public function sentmsgs(){
        return $this->belongsTo('App\User','msg_to','id');
    }

    public function inboxmsgs(){
        return $this->belongsTo('App\User','msg_from','id');
    }
}
