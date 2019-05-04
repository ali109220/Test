<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Doctor extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'arabic_name' => $this->arabic_name,
            'img_src' => $this->img_src,
            'title' => $this->title,
            'gender' => $this->gender,
            'specialty_id' => $this->specialty_id,
            'specialty_name' => $this->specialty_name,
            'sub_specialty_id' => $this->sub_specialty_id,
            'sub_specialty_name' => $this->sub_specialty_name,
            'city_id' => $this->city_id,
            'city_name' => $this->city_name,
            'area_id' => $this->area_id,
            'area_name' => $this->area_name,
            'mobile_phone' => $this->mobile_phone,
            'clinic_phone' => $this->clinic_phone,
            'address' => $this->address,
            'arabic_address' => $this->arabic_address,
            'detection_fee' => $this->detection_fee,
            'wiating_duration' => $this->wiating_duration,
            'first_appointment_morning' => $this->first_appointment_morning,
            'last_appointment_morning' => $this->last_appointment_morning,
            'step_morning' => $this->step_morning,
            'step_night' => $this->step_night,
            'first_appointment_night' => $this->first_appointment_night,
            'last_appointment_night' => $this->last_appointment_night,
            'priority_from_here' => $this->priority_from_here,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
