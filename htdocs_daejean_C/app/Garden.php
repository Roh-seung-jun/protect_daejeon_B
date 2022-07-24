<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Garden extends Model
{
    public $timestamps = false;
    protected $guarded = [];


    public function calendars(){
        return $this->hasMany('App\Calendar');
    }
}
