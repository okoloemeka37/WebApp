<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function DashSearch(Request $request){
         $field=$request->validate(['search'=>'required',]);
        try {
            
       $name = Group::whereLike('subject', '%' . $field['search'] . '%')
    ->get()
    ->map(function ($rt) {
        $rt->url = encrypt($rt->group_id);
        return $rt;
    });
            return response()->json(['success' => true,'data'=>$name],200);
        } catch (\Throwable $th) {
             return response()->json(['success' => false,'error' => ['message' => $th->getMessage(),]],500);
        }
    }
}
