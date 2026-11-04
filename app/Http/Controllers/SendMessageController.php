<?php

namespace App\Http\Controllers;
use App\Models\Chats;
use App\Models\Contacts;
use App\Models\Participant;

use \Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\agentTomessage;
use App\Services\WhatsAppGroupService;
use Illuminate\Http\Request;

class SendMessageController extends Controller
{
    protected WhatsAppGroupService $whatsappService;

function __construct(WhatsAppGroupService $whatsappService){
        $this->whatsappService=$whatsappService;
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

                agentTomessage::create([
                        'group_id'=>$sentMessage['key']['remoteJid']??'',
                        'message_id'=>$sentMessage['key']['id']??'',
                        'agent_id'=>$request['user_id'],
                        'replyInterval'=>null
                ]);
                
            }
            return response()->json(['success' => true],200);
            }catch (\Throwable $e) {
              return response()->json([
        'success' => false,
        'error' => ['message' => $e->getMessage(),'line'=>$e->getLine(),'file'=>$e->getFile()]
    ], 500);
       }


    }

}
