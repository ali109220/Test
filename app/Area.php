<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    public function city()
    {
        return $this->belongsTo('App\City');
    }
    public function doctors()
    {
        return $this->hasMany('App\Doctor');
    }
}
