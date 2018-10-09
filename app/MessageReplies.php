<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MessageReplies extends Model
{
    public function message()
    {
        return $this->belongsTo('App\Messages','repMSG','msg_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User','user_id','id');
    }
}
