<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Subspecialty;
use App\Http\Resources\Subspecialty as SubSpecialtyResource;

class SubspecialtyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function all(){
        $All = Subspecialty::select('id','name')->get();
        return $All;
    }
    public function getAllWithFilters(Request $req){
        $filters = $req->input('where_filters');
        if(count($filters)==0){
            $subSpecialties = DB::table('subSpecialties')->join('specialties','subSpecialties.specialty_id','=','specialties.id')->
            select('subSpecialties.*', 'specialties.name as specialty_name')->
            orderBy('subSpecialties.created_at','desc')->
            paginate(6);
            return SubSpecialtyResource::collection($subSpecialties);
        }
        else{
            $subSpecialties = DB::table('subSpecialties')->join('specialties','subSpecialties.specialty_id','=','specialties.id')->
            select('subSpecialties.*', 'specialties.name as specialty_name')->
            orderBy('subSpecialties.created_at','desc')->where(function ($q) use ($filters) {
                foreach ($filters as $filter) {
                    $text = '';
                    foreach ($filter as $element) {
                        $text =$text.';'.$element;
                    }
                    $vars_filter = explode(';',$text);
                    if($vars_filter[4] == 'date'){
                        $q->Where('subSpecialties.'.$vars_filter[1], $vars_filter[2], $vars_filter[3].' 00:00:00.0');
                    }
                    else if($vars_filter[4] == 'time'){
                        $q->Where('subSpecialties.'.$vars_filter[1], $vars_filter[2], '2000-01-01 '.$vars_filter[3]);
                    }
                    else{
                        $q->Where('subSpecialties.'.$vars_filter[1], $vars_filter[2], $vars_filter[3]);
                    }
                }
            })->paginate(6);
            return SubSpecialtyResource::collection($subSpecialties);
        }
    }
    public function store(Request $req){
        $subspecialties = Subspecialty::all();
        foreach ($subspecialties as $_subspecialty) {
            if($_subspecialty->order == $req->input('order'))
                return response()->json(['error'=>'order','msg'=>'this order value exist']);
        }
        $subspecialty = new Subspecialty();
        $subspecialty->name = $req->input('name');
        $subspecialty->arabic_name = $req->input('arabic_name');
        $subspecialty->order = $req->input('order');
        $subspecialty->specialty_id = $req->input('specialty_id');
        if($subspecialty->save()){
           return new SubSpecialtyResource($subspecialty);
        }
    
    }
    public function update(Request $req, $id){
        $subspecialties = Subspecialty::all();
        foreach ($subspecialties as $_subspecialty) {
            if($_subspecialty->order == $req->input('order'))
                return response()->json(['error'=>'order','msg'=>'this order value exist']);
        }
        $subspecialty = Subspecialty::findOrFail($id);
        $subspecialty->name = $req->input('name');
        $subspecialty->arabic_name = $req->input('arabic_name');
        $subspecialty->order = $req->input('order');
        $subspecialty->specialty_id = $req->input('specialty_id');
        if($subspecialty->save()){
           return new SubSpecialtyResource($subspecialty);
        }
    
    }
}
