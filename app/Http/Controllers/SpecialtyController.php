<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Specialty;
use App\Subspecialty;
use App\Http\Resources\Specialty as SpecialtyResource;


class SpecialtyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function all(){
        $All = Specialty::select('id','name')->get();
        return $All;
    }
    public function getAllWithFilters(Request $req){
        $filters = $req->input('where_filters');
        if(count($filters)==0){
            $specialties = Specialty::orderBy('created_at','desc')->paginate(6);
            return SpecialtyResource::collection($specialties);
        }
        else{
            $specialties = Specialty::where(function ($q) use ($filters) {
                foreach ($filters as $filter) {
                    $text = '';
                    foreach ($filter as $element) {
                        $text =$text.';'.$element;
                    }
                    $vars_filter = explode(';',$text);
                    if($vars_filter[4] == 'date'){
                        $q->Where($vars_filter[1], $vars_filter[2], $vars_filter[3].' 00:00:00.0');
                    }
                    else if($vars_filter[4] == 'time'){
                        $q->Where($vars_filter[1], $vars_filter[2], '2000-01-01 '.$vars_filter[3]);
                    }
                    else{
                        $q->Where($vars_filter[1], $vars_filter[2], $vars_filter[3]);
                    }
                }
                })->paginate(6);
                return SpecialtyResource::collection($specialties);
        }
    }
    public function store(Request $req){
        $specialties = Specialty::all();
        foreach ($specialties as $_speciilty) {
            if($_speciilty->order == $req->input('order'))
                return response()->json(['error'=>'order','msg'=>'this order value exist']);
        }
        $specialty = new Specialty();
        $specialty->name = $req->input('name');
        $specialty->arabic_name = $req->input('arabic_name');
        $specialty->order = $req->input('order');
        if($specialty->save()){
            return new SpecialtyResource($specialty);
        }
        
    }
    public function update(Request $req, $id){
        $specialties = Specialty::all();
        foreach ($specialties as $_speciilty) {
            if($_speciilty->id != $id && $_speciilty->order == $req->input('order'))
                return response()->json(['error'=>'order','msg'=>'this order value exist']);
        }
        $specialty = Specialty::findOrFail($id);
        $specialty->name = $req->input('name');
        $specialty->arabic_name = $req->input('arabic_name');
        $specialty->order = $req->input('order');
        if($specialty->save()){
            return new SpecialtyResource($specialty);
        }
    }
}
