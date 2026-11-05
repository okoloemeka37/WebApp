<?php

namespace App\Http\Controllers;

use App\Models\Chats;
use App\Models\Contacts;
use App\Models\Participant;
use App\Services\WhatsAppGroupService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    
protected WhatsAppGroupService $whatsappService;

function __construct(WhatsAppGroupService $whatsappService){
        $this->whatsappService=$whatsappService;
}


    public function groupChat($id){
       
    try {
            $group_Id= \decrypt($id);
 
                
              
             $ftChats=Chats::where('group_id',$group_Id)->get();

          $contacts=Contacts::get();
        return view('Agent/Chats',compact('ftChats','contacts','group_Id'));  
         
            } catch (\Throwable $th) {
           \Log::info($th);
        }

    }


    public function GetContacts(){
        $contacts=$this->whatsappService->fetchAllContacts();
        dd($contacts);
      try {
          foreach ($contacts as $contact) {
            if ($contact['type']==='contact') {
               Contacts::updateOrCreate(['contact_id'=>$contact['remoteJid']],[
             'contact_id'=>$contact['remoteJid']??'',
             'name'=>$contact['pushName']??'',
             'profilePicUrl'=>$contact['profilePicUrl']??''
               ]);
            }
        }
      } catch (\Throwable $th) {
         \Log::error($th);
      }
    }


    public function sendMessage(Request $request){
            try {
                $field=$request->validate(['message_text'=>'required',]);

            $sentMessage=$this->whatsappService->sendGroupMessage($request['groupId'],$field['message_text']);
   
            if (count($sentMessage)!==0) {
                  
                   Chats::create([
                        'group_id'=>$sentMessage['key']['remoteJid']??'',
                        'message_id'=>$sentMessage['key']['id']??'',
                        'sender_name'=>'Zyler',
                        'message_type'=>$sentMessage['messageType'],
                        'body'=>$sentMessage['message']['conversation'],
                        'is_from_me'=>$sentMessage['key']['fromMe']??'',
                        'source'=>$sentMessage['source'],
                        'message_timestamp'=>Carbon::createFromTimestamp($sentMessage['messageTimestamp'])->toDateTimeString(),
                        'status'=>'SENT'
                   ]); 
                
            }
            return response()->json(['success' => true],200);
            }catch (\Throwable $e) {
              return response()->json([
        'success' => false,
        'error' => ['message' => $e->getMessage(),]
    ], 500);
       }


    }



}
