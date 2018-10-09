<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'fname', 'lname', 'phone', 'cpassword', 'idtype', 'careerfield', 'username', 'gender', 'photo', 'photo_path', 'email', 'password', 'user_type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'created_at', 'updated_at', 'photo_path'
    ];

    public function request(){
        return $this->hasMany('App\MentorRequest','mentee','id');
    }

    public function messages(){
        return $this->hasMany('App/Messages','msg_to','id');
    }

    public function replies()
    {
        return $this->hasMany('App\MessageReplies','user_id','msg_id');
    }

    public function goals()
    {
        return $this->hasMany('App\Goals');
    }

    public function achievement(){
        return $this->belongsTo('App\Achievement','achUser','id');
    }

    public function comMessage(){
        return $this->hasMany('App\Community','comMsgBy','id');
    }

    public function reports(){
        return $this->hasMany('App\Report','id','repBy');
    }
}
