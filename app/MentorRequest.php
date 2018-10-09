<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MentorRequest extends Model
{
    public function user(){
        return $this->belongsTo('App\User','mentee','id');
    }
}
