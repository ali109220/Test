<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Subspecialty extends Resource
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
            'order' => $this->order,
            'specialty_id' => $this->specialty_id,
            'specialty_name' => $this->specialty_name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
