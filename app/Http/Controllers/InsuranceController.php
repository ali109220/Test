<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Insurance;
use App\Http\Resources\Insurance as InsuranceResource;

class InsuranceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function all(){
        $All = Insurance::select('id','name')->get();
        return $All;
    }
    public function allInsurancesWithoutAdded($id){
        $insurances =  DB::table('insurances')
        ->leftJoin('insurancesdoctors', 'insurances.id', '=', 'insurancesdoctors.insurance_id')
        ->whereNull('insurancesdoctors.doctor_id')
        ->orWhere('insurancesdoctors.doctor_id','<>',$id)
        ->select('insurances.id', 'insurances.name')
        ->get();
        return $insurances;
    }
    public function getAllWithFilters(Request $req){
            $filters = $req->input('where_filters');
            if(count($filters)==0){
                $insurances = Insurance::orderBy('created_at','desc')->paginate(6);
                return InsuranceResource::collection($insurances);
            }
            else{
                $insurances = Insurance::where(function ($q) use ($filters) {
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
                    return InsuranceResource::collection($insurances);
            }
    }
    public function store(Request $req){
        $insurances = Insurance::all();
        foreach ($insurances as $_insurance) {
            if($_insurance->order == $req->input('order'))
                return response()->json(['error'=>'order','msg'=>'this order value exist']);
        }
        $insurance = new Insurance();
        $insurance->name = $req->input('name');
        $insurance->arabic_name = $req->input('arabic_name');
        $insurance->order = $req->input('order');
        $insurance->national = $req->input('national');
        if($insurance->save()){
            return new InsuranceResource($insurance);
        }
        
    }
    public function update(Request $req, $id){
        $insurances = Insurance::all();
        foreach ($insurances as $_insurance) {
            if($_insurance->order == $req->input('order'))
                return response()->json(['error'=>'order','msg'=>'this order value exist']);
        }
        $insurance = Insurance::findOrFail($id);
        $insurance->name = $req->input('name');
        $insurance->arabic_name = $req->input('arabic_name');
        $insurance->order = $req->input('order');
        $insurance->national = $req->input('national');
        if($insurance->save()){
            return new InsuranceResource($insurance);
        }
        
    }
}
