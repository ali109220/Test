<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subspecialty extends Model
{
    public function specialty()
    {
        return $this->belongsTo('App\Specialty');
    }
    public function doctors()
    {
        return $this->hasMany('App\Doctor');
    }
}
