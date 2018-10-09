<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    protected $hidden = ['created_at', 'updated_at','comMsg_id', 'comMsgTime'];

    public function user(){
        return $this->belongsTo('App\User','comMsgBy','id');
    }
}
