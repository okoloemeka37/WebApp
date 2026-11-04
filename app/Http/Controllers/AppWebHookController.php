<?php

namespace App\Http\Controllers;
use App\Events\HookEvent;
use App\Models\Chats;
use App\Models\Contacts;
use App\Models\Participant;
use Carbon\Carbon;
use Illuminate\Http\Request;


class AppWebHookController extends Controller
{
    public function handle(Request $request)
    {
       
        // 1. Capture the raw event payload coming from the Docker container
        $event = $request->input('event');
        $data = $request->input('data');

        \Log::info('WhatsApp Webhook Received: ' . $event);
        
        \Log::info($data);
        // 2. Handle different event types emitted by Evolution API
    if (array_key_exists('key',$data)) {
           
        if (count($data) !==0) {
            if (str_ends_with($data['key']['remoteJid'],'@g.us')) {
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

  \Log::info($data);

       try {
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
                        'participant_contact_id'=>'',
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
                }else{\Log::info($chj);}}
           } catch (\Throwable $th) {
            throw $th;
           } 
    }

}
