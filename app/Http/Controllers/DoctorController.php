<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Doctor;
use App\Http\Resources\Doctor as DoctorResource;

class DoctorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function allTitles(){
        $array = array(
            array(
                "id" => '0',
                "name" => "Specialist"
            ),
            array(
                "id" => '1',
                "name" => "Professor"
            ),
            array(
                "id" => '2',
                "name" => "Consultant"
            ));
        return $array;
    }
    public function allGenders(){
        $array = array(
            array(
                "id" => '0',
                "name" => "Male"
            ),
            array(
                "id" => '1',
                "name" => "Female"
            ));
        return $array;
    }
    public function all(){
        $All = DB::table('doctors')->join('specialties','doctors.specialty_id','=','specialties.id')->
        join('subSpecialties','doctors.sub_specialty_id','=','subSpecialties.id')->
        join('cities','doctors.city_id','=','cities.id')->
        join('areas','doctors.area_id','=','areas.id')->
        select('doctors.*', 'specialties.name as specialty_name', 
        'subSpecialties.name as sub_specialty_name', 
        'cities.name as city_name', 
        'areas.name as area_name')->
        orderBy('doctors.created_at','desc')->
        paginate(6);
        return $All;
    }
    public function getAllWithFilters(Request $req){
        $filters = $req->input('where_filters');
        if(count($filters)==0){
            $doctors = DB::table('doctors')->join('specialties','doctors.specialty_id','=','specialties.id')->
            join('subSpecialties','doctors.sub_specialty_id','=','subSpecialties.id')->
            join('cities','doctors.city_id','=','cities.id')->
            join('areas','doctors.area_id','=','areas.id')->
            select('doctors.*', 'specialties.name as specialty_name', 
            'subSpecialties.name as sub_specialty_name', 
            'cities.name as city_name', 
            'areas.name as area_name')->
            orderBy('doctors.created_at','desc')->
            paginate(6);
            return DoctorResource::collection($doctors);
        }
        else{
            $doctors = DB::table('doctors')->join('specialties','doctors.specialty_id','=','specialties.id')->
            join('subSpecialties','doctors.sub_specialty_id','=','subSpecialties.id')->
            join('cities','doctors.city_id','=','cities.id')->
            join('areas','doctors.area_id','=','areas.id')->
            select('doctors.*', 'specialties.name as specialty_name', 
            'subSpecialties.name as sub_specialty_name', 
            'cities.name as city_name', 
            'areas.name as area_name')->
            orderBy('doctors.created_at','desc')->
            where(function ($q) use ($filters) {
                foreach ($filters as $filter) {
                    $text = '';
                    foreach ($filter as $element) {
                        $text =$text.';'.$element;
                    }
                    $vars_filter = explode(';',$text);
                    if($vars_filter[4] == 'date'){
                        $q->Where('doctors.'.$vars_filter[1], $vars_filter[2], $vars_filter[3].' 00:00:00.0');
                    }
                    else if($vars_filter[4] == 'time'){
                        $q->Where('doctors.'.$vars_filter[1], $vars_filter[2], '2000-01-01 '.$vars_filter[3]);
                    }
                    else{
                        $q->Where('doctors.'.$vars_filter[1], $vars_filter[2], $vars_filter[3]);
                    }
                }
            })->paginate(6);
            return DoctorResource::collection($doctors);
        }
    }
    public function uploadImage($img){
        list($type,$image_data) = explode(';',$img);
        list(,$image_extension) = explode('/',$type);
        list(,$_image) = explode(',',$image_data);
        $image_name = "storage/images/".uniqid().'.'.$image_extension;
        $source = fopen($img,'r');
        $dest = fopen($image_name,'w');
        stream_copy_to_stream($source,$dest);
        fclose($source);
        fclose($dest);
        return $image_name;
    }
    public function saveDoctor(Request $req, $doctor){
        //doctor obj
        $doctor->name = $req->input('name');
        $doctor->arabic_name = $req->input('arabic_name');
        //image
        $doctor->img_src = '';
        if($req->input('img_src')!=NULL || $req->input('img_src')!=''){
            $doctor->img_src = $this->uploadImage($req->input('img_src'));
        }
        //enums
        switch ($req->input('title')) {
            case '0':
                $doctor->title = 'specialist';
                break;
            case '1':
                $doctor->title = 'professor';
                break;
            case '2':
                $doctor->title = 'consultant';
                break;
        }
        switch ($req->input('gender')) {
            case '0':
                $doctor->gender = 'male';
                break;
            case '1':
                $doctor->gender = 'female';
                break;
        }
        
        //refs
        $doctor->specialty_id = $req->input('specialty_id');
        $doctor->city_id = $req->input('city_id');
        if($req->input('sub_specialty_id')!=NULL || $req->input('sub_specialty_id')!='')
            $doctor->sub_specialty_id = $req->input('sub_specialty_id');
        if($req->input('area_id')!=NULL || $req->input('area_id')!='')
            $doctor->area_id = $req->input('area_id');
        //additional
        $doctor->mobile_phone = $req->input('mobile_phone');
        $doctor->clinic_phone = $req->input('clinic_phone');
        $doctor->address = $req->input('address');
        $doctor->arabic_address = $req->input('arabic_address');
        //return response()->json($doctor);
        if($req->input('detection_fee')!=NULL || $req->input('detection_fee')!='')
            $doctor->detection_fee = $req->input('detection_fee');
        if($req->input('wiating_duration')!=NULL || $req->input('wiating_duration')!='')
            $doctor->wiating_duration = $req->input('wiating_duration');
        if($req->input('step_morning')!=NULL || $req->input('step_morning')!='')
            $doctor->step_morning = $req->input('step_morning');
        if($req->input('step_night')!=NULL || $req->input('step_night')!='')
            $doctor->step_night = $req->input('step_night');
        
        
        //time fields
        if($req->input('first_appointment_morning') != "" && $req->input('first_appointment_morning') != NULL)
            $doctor->first_appointment_morning = date('Y-m-d H:i:s', strtotime($req->input('first_appointment_morning')));  
        if($req->input('last_appointment_morning') != "" && $req->input('last_appointment_morning') != NULL)
            $doctor->last_appointment_morning = date('Y-m-d H:i:s', strtotime($req->input('last_appointment_morning')));  
        if($req->input('first_appointment_night') != "" && $req->input('first_appointment_night') != NULL)
            $doctor->first_appointment_night = date('Y-m-d H:i:s', strtotime($req->input('first_appointment_night')));  
        if($req->input('last_appointment_night') != "" && $req->input('last_appointment_night') != NULL)
            $doctor->last_appointment_night = date('Y-m-d H:i:s', strtotime($req->input('last_appointment_night')));  
        //boolean field
        $doctor->priority_from_here = $req->has('priority_from_here'); 
        
        if($doctor->save()){
            return $doctor;
        }
        
    }
    public function store(Request $req){
        //server validation

        //doctorSaving
        $doctor = new Doctor();
        $doctor = $this->saveDoctor($req,$doctor);
        return new DoctorResource($doctor);
    }
    public function update(Request $req, $id){
        //server validation

        //doctorSaving
        $doctor = Doctor::findOrFail($id);
        $doctor = $this->saveDoctor($req,$doctor);
        return new DoctorResource($doctor);
    }
    public function addInsurance(Request $req){
        //server validation

        //doctorSaving
        DB::table('insurancesDoctors')->insert(
            ['doctor_id' => $req->input('doctor_id'),
             'insurance_id' => $req->input('insurance_id')]
        );

    }
}
