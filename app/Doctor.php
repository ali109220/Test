<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    public function users()
    {
        return $this->belongsToMany('App\User');
    }
    public function insurances()
    {
        return $this->belongsToMany('App\Insurance');
    }
    public function city()
    {
        return $this->belongsTo('App\City');
    }
    public function area()
    {
        return $this->belongsTo('App\Area');
    }
    public function specialty()
    {
        return $this->belongsTo('App\Specialty');
    }
    public function subSpecliaty()
    {
        return $this->belongsTo('App\Subspecliaty');
    }
}
