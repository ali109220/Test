<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\City;
use App\Http\Resources\City as CityResource;

class CityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function all(){
        $All = City::select('id','name')->get();
        return $All;
    }
    public function index(Request $req){
            $filters = $req->input('where_filters');
            if(count($filters)==0){
                $Cities = City::orderBy('created_at','desc')->paginate(6);
                return CityResource::collection($Cities);
            }
            else{
                $Cities = City::where(function ($q) use ($filters) {
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
                    return CityResource::collection($Cities);
            }
    }
    public function store(Request $req){
        $Cities = City::all();
        foreach ($Cities as $_city) {
            if($_city->order == $req->input('order'))
                return response()->json(['error'=>'order','msg'=>'this order value exist']);
        }
        $city = new City();
        $city->name = $req->input('name');
        $city->arabic_name = $req->input('arabic_name');
        $city->order = $req->input('order');
        if($city->save()){
            return new CityResource($city);
        }
        
    }
}
