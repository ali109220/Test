<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public function areas()
    {
        return $this->hasMany('App\Area');
    }
    public function doctors()
    {
        return $this->hasMany('App\Doctor');
    }
}
