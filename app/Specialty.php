<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Specialty extends Model
{
    public function sub_specialty()
    {
        return $this->hasMany('App\Subspecialty');
    }
    public function doctors()
    {
        return $this->hasMany('App\Doctor');
    }
}
