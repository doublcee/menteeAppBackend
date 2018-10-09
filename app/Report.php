<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'repBy','repTo','reason','repBody','repDate'
    ];

    public function user(){
        return $this->belongsTo('App\User','repBy','id');
    }
}
