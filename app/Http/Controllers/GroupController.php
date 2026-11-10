<?php

namespace App\Http\Controllers;

use App\Models\chat\Reaction;
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
 
                
           $reactions = Reaction::where('group_id', $group_Id)->whereNot('body',NULL)->get()->toArray();
$ftChats = Chats::where('group_id', $group_Id)->get();

foreach ($ftChats as $chats) {

    $thg = array_filter($reactions, function($reaction) use ($chats) {
        return $reaction['reactionTo'] == $chats['message_id'];
    });
$chats['reaction']=$thg;
}



          $contacts=Contacts::get();
        return view('Agent/Chats',compact('ftChats','contacts','group_Id'));  
         
            } catch (\Throwable $th) {
           \Log::info($th);
        }

    }


   public function GetContacts()
{
    try {
        $contacts = collect($this->whatsappService->fetchAllContacts())
            ->where('type', 'contact');

        $contacts->chunk(100)->each(function ($chunk) {

            $data = $chunk->map(function ($contact) {
                return [
                    'contact_id' => $contact['remoteJid'],
                    'name' => $contact['pushName'] ?? '',
                    'profilePicUrl' => $contact['profilePicUrl'] ?? '',
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            })->values()->toArray();

            Contacts::upsert(
                $data,
                ['contact_id'],
                ['name', 'profilePicUrl', 'updated_at']
            );

        });

    } catch (\Throwable $th) {
        \Log::error($th);
    }
}

  /*   public function sendMessage(Request $request){
            try {
                $field=$request->validate(['message_text'=>'required',]);

            //$sentMessage=$this->whatsappService->sendGroupMessage($request['groupId'],$field['message_text']);
   
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
 */


}
