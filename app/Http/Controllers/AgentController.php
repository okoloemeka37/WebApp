<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Services\WhatsAppGroupService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\VarDumper\VarDumper;

class AgentController extends Controller
{

protected WhatsAppGroupService $whatsappService;

function __construct(WhatsAppGroupService $whatsappService){
        $this->whatsappService=$whatsappService;
}


    public function login(Request $request){
       $field=$request->validate([
            'email'=>'required',
            'password'=>'required'
       ]);

       try {
        if (Auth::guard('agent')->attempt(['email' => $field['email'], 'password' => $field['password']],$request->boolean('remember'))) {
        $request->session()->regenerate();

        return redirect()->route('AgentDash');
       }else{
        return back()->withErrors(['failed'=>'Credentials Not Valid']);
       } 
       } catch (\Throwable $th) {
        throw $th;
       }
    }


    public function AgentDash(){
    $countGroup=Group::count();
    $groups=Group::paginate(10);

        return view('Agent.Dash',compact('groups','countGroup'));
    }



    public function AddGroups(){
       $groups=$this->whatsappService->fetchAllGroups();

      
        foreach ($groups as $group) {
            Group::updateOrCreate(
                ['group_id' => $group['id']],
                [
                    'linkedParent' => $group['linkedParent'] ?? '',
                    'isCommunityAnnounce' => $group['isCommunityAnnounce'] ?? '',
                    'isCommunity' => $group['isCommunity'] ?? '',
                    'announce' => $group['announce'] ?? '',
                    'restrict' => $group['restrict'] ?? '',
                    'descId' => $group['descId'] ?? '',
                    'desc' => $group['desc'] ?? '',
                    'owner' => $group['owner'] ?? '',
                    'group_creation_time' => Carbon::createFromTimestamp($group['creation'] ?? 0)->toDateTimeString(),
                    'size' => $group['size'] ?? '',
                    'pictureUrl' => $group['pictureUrl'] ?? '',
                    'subjectTime' => Carbon::createFromTimestamp($group['subjectTime'] ?? 0)->toDateTimeString(),
                    'subjectOwner' => $group['subjectOwner'] ?? '',
                    'subject' => $group['subject'] ?? '',
                    'group_id' => $group['id'],
                ]
            );
        }
        dd($groups);
    }

    public function AgentLogout(Request $request){
        Auth::guard('agent')->logout();
         $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('GetAgentLogin');
    }
}
