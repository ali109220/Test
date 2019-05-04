<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Area;
use App\Http\Resources\Area as AreaResource;

class AreaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function all(){
        $All = Area::select('id','name')->get();
        return $All;
    }
    public function getAllWithFilters(Request $req){
        $filters = $req->input('where_filters');
        if(count($filters)==0){
            $areas = DB::table('areas')->join('cities','areas.city_id','=','cities.id')->
            select('areas.*', 'cities.name as city_name')->
            orderBy('areas.created_at','desc')->
            paginate(6);
            return SubSpecialtyResource::collection($areas);
        }
        else{
            $areas = DB::table('areas')->join('cities','areas.city_id','=','cities.id')->
            select('areas.*', 'cities.name as city_name')->
            orderBy('areas.created_at','desc')->where(function ($q) use ($filters) {
                foreach ($filters as $filter) {
                    $text = '';
                    foreach ($filter as $element) {
                        $text =$text.';'.$element;
                    }
                    $vars_filter = explode(';',$text);
                    if($vars_filter[4] == 'date'){
                        $q->Where('areas.'.$vars_filter[1], $vars_filter[2], $vars_filter[3].' 00:00:00.0');
                    }
                    else if($vars_filter[4] == 'time'){
                        $q->Where('areas.'.$vars_filter[1], $vars_filter[2], '2000-01-01 '.$vars_filter[3]);
                    }
                    else{
                        $q->Where('areas.'.$vars_filter[1], $vars_filter[2], $vars_filter[3]);
                    }
                }
            })->paginate(6);
            return SubSpecialtyResource::collection($areas);
        }
    }
    public function store(Request $req){
        $areas = Area::all();
        foreach ($areas as $_area) {
            if($_area->order == $req->input('order'))
                return response()->json(['error'=>'order','msg'=>'this order value exist']);
        }
        $area = new Area();
        $area->name = $req->input('name');
        $area->arabic_name = $req->input('arabic_name');
        $area->order = $req->input('order');
        $area->city_id = $req->input('city_id');
        if($area->save()){
           return new AreaResource($area);
        }
    
    }
}
