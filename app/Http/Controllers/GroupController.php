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
           $messages=$this->whatsappService->getGroupMessages($group_Id);
          $participants=$this->whatsappService->fetchGroupInfo($group_Id)['participants'];


              foreach ($participants as $pat) {
                Participant::updateOrCreate(['group_id'=>$group_Id,'phoneNumber'=>$pat['phoneNumber']],[
                    'isAdmin'=>$pat['admin']=='null'?'false':'true',
                    'phoneNumber'=>$pat['phoneNumber'],
                    'participant_id'=>$pat['id'],
                    'group_id'=>$group_Id
                    ]);
            }

         $chats=$messages['messages']['records'];

         foreach ($chats as $chat) {
            if ($chat['messageType']=='conversation') {
                
              Chats::updateOrCreate(['message_id'=>$chat['key']['id']],[
                'message_timestamp'=>Carbon::createFromTimestamp($chat['messageTimestamp']??0)->toDateTimeString()
            ,'quoted_message_id' => $chat['contextInfo']['stanzaId'] ?? null,
            'is_from_me'=>$chat['key']['fromMe'],
            'body'=>$chat['message']['conversation'],
            'message_type'=>$chat['messageType'],
            'sender_name'=>$chat['pushName'],
            'sender_id'=>$chat['pushName'],
            'group_id'=>$chat['key']['remoteJid'],
            'message_id'=>$chat['key']['id'],
            'participant_contact_id'=>$chat['key']['participantAlt']??'',
            'source'=>$chat['source']??'',
            'status'=>'DROP'


            ]);
            
         
            }
           
            }
 
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
