<?php

namespace App\Http\Controllers;
use App\Events\HookEvent;
use App\Models\chat\Reaction;
use App\Models\Chats;
use App\Models\Contacts;
use App\Models\Participant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Log;


class AppWebHookController extends Controller
{
    public function handle(Request $request)
    {
       
        // 1. Capture the raw event payload coming from the Docker container
        $event = $request->input('event');
        $data = $request->input('data');

        
        // 2. Handle different event types emitted by Evolution API
    if (array_key_exists('key',$data)) {
           
        if (count($data) !==0) {
            if (str_ends_with($data['key']['remoteJid'],'@g.us')) {

            \Log::info('WhatsApp Webhook Received: ' . $event);
        
        
                switch ($event) {
            case 'messages.upsert':
                
                // A new message was received or sent
                $this->handleMessageUpsert($data);
                break;

            case 'messages.update':
                // A message status changed (e.g., PENDING -> SERVER_ACK -> DELIVERY_ACK)
               // $this->handleMessageUpdate($data);
                break;

            default:
                // Other events (connection updates, group updates, etc.)
                break;
        } 

            }
        }
   }
        return response()->json(['status' => 'success']); 
    }


  public function handleMessageUpsert($data){

       try {
       
        $messType=$data['messageType'];
        switch ($messType) {
            case 'conversation':
                $this->handleConversation($data);
                break;
            
            case 'reactionMessage':
                $this->handleReaction($data); 
                break; 

            default:
                # code...
                break;
        }
            
           } catch (\Throwable $th) {
            throw $th;
           } 
    }
public function handleConversation($data){
    Log::info($data);
         if (count($data) !==0) {
        $chj=null;
        if(array_key_exists('conversation',$data['message'])){
            
                if ($data['key']['fromMe']) {
                   $chj= Chats::create([
                        'group_id'=>$data['key']['remoteJid']??'',
                        'message_id'=>$data['key']['id']??'',
                        'sender_name'=>'Zyler',
                        'message_type'=>$data['messageType'],
                        'body'=>$data['message']['conversation'],
                        'is_from_me'=>$data['key']['fromMe']??'',
                        'source'=>$data['source'],
                        'reactionTo'=>null,
                        'participant_contact_id'=>'2349017251972@s.whatsapp.net',
                        'quoted_message_id' => $data['contextInfo']['stanzaId'] ?? null,                        'message_timestamp'=>Carbon::createFromTimestamp($data['messageTimestamp'])->toDateTimeString(),
                        'status'=>'DROP'
                   ]); 
                }else{
                    $pName='';
                    if (array_key_exists('participantAlt',$data['key'])) {
                          $part=Participant::where('phoneNumber',$data['key']['participantAlt'])->get();
                    if (count($part)==0) {
                          Participant::create([
                    'isAdmin'=>'true',
                    'phoneNumber'=>$data['key']['participantAlt'],
                    'participant_id'=>$data['key']['participant'],
                    'group_id'=>$data['key']['remoteJid']
                    ]);
                    }
                    $contact=Contacts::where('contact_id',$data['key']['participantAlt'])->get();
                    if (count($contact)!==0) {
                        if (strlen($contact[0]['name'])!==0) {
                          $pName=$contact[0]['name'];
                        }else{$pName=$data['pushName'];}
                       
                    }
                    }
                  
                     $chj=Chats::create([
                        'group_id'=>$data['key']['remoteJid']??'',
                        'message_id'=>$data['key']['id']??'',
                        'sender_name'=>$pName,
                        'message_type'=>$data['messageType'],
                        'body'=>$data['message']['conversation'],
                        'is_from_me'=>$data['key']['fromMe']??'',
                        'source'=>$data['source']??'',
                         'reactionTo'=>null,
                        'participant_contact_id'=>$data['key']['participantAlt']??'',
                        'quoted_message_id' => $data['contextInfo']['stanzaId'] ?? null,                        'message_timestamp'=>Carbon::createFromTimestamp($data['messageTimestamp'])->toDateTimeString(),
                        'status'=>'DROP'
                   ]); 

                }

        }
                   
                    if ($chj !== null) {
                    
                    
                  $lastId= $chj->id;
                  $recent=Chats::where('id',$lastId)->get();
                 
                   event(new HookEvent($recent));
                }else{Log::info($chj);}
                
                }
}

public function handleReaction($data){
    Log::info($data);
if (count($data) !==0) {
$chj=null;

                    if ($data['key']['fromMe']) {
                         $chj=Reaction::updateOrCreate(['reaction_id'=>$data['key']['id'],'group_id'=>$data['key']['remoteJid'],'participant_contact_id'=>'2349017251972@s.whatsapp.net'],
            [
                       'group_id'=>$data['key']['remoteJid']??'',
                        'reaction_id'=>$data['key']['id']??'',

                        'body'=>$data['message']['reactionMessage']['text'],
                        'is_from_me'=>$data['key']['fromMe']??'',
                       
                        'reactionTo'=>$data['message']['reactionMessage']['key']['id'],
                        'participant_contact_id'=>'2349017251972@s.whatsapp.net',
                        'message_type'=>$data['messageType'],
                        'message_timestamp'=>Carbon::createFromTimestamp($data['messageTimestamp'])->toDateTimeString(),
                        
                        'status'=>'DROP'
            ]);
                    }else{
            $chj=Reaction::updateOrCreate(['group_id'=>$data['key']['remoteJid'],'participant_contact_id'=>$data['key']['participantAlt']],
            [
                       'group_id'=>$data['key']['remoteJid']??'',
                        'reaction_id'=>$data['key']['id']??'',

                        'body'=>$data['message']['reactionMessage']['text']??NULL,
                        'is_from_me'=>$data['key']['fromMe']??'',
                       
                        'reactionTo'=>$data['message']['reactionMessage']['key']['id'],
                        'participant_contact_id'=>$data['key']['participantAlt'],
                         'message_type'=>$data['messageType'],
                        'message_timestamp'=>Carbon::createFromTimestamp($data['messageTimestamp'])->toDateTimeString(),
                        
                        'status'=>'DROP'
            ]);
                    }

                  if ($chj !== null) {  
                  $lastId= $chj->id;
                  $recent=Reaction::where('id',$lastId)->get();
                  Log::info($recent);
                  event(new HookEvent($recent));
                }else{}

                

}


}


}


