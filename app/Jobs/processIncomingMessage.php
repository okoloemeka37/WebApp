<?php

namespace App\Jobs;

use App\Events\HookEvent;
use App\Models\chat\MediaDetails;
use App\Models\chat\Reaction;
use App\Models\Chats;
use Log;
use App\Models\Contacts;
use App\Models\Participant;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class processIncomingMessage implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public int $timeout = 90;
    public int $backoff = 5;
    /**
     * Create a new job instance.
     */
    public function __construct(public array $payload)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
         try {
       
        $messType=$this->payload['messageType'];
        Log::info($this->payload['messageType']);
        switch ($messType) {
            case 'conversation':
                $this->handleConversation($this->payload);
                break;
            
            case 'reactionMessage':
                $this->handleReaction($this->payload); 
                break; 
                
            case 'imageMessage':
                $this->handleImage($this->payload); 
                break; 

            case "audioMessage":
                    $this->handleVoiceNote($this->payload);
                    break;
            case "videoMessage":
                    $this->handleVideoMessage($this->payload);
                    break;
            default:
                # code...
                break;
        }
            
           } catch (\Throwable $th) {
            throw $th;
           } 

          /* logger()->info('Processing WhatsApp webhook', [
            'payload' => ""
        ]); */
    }



    public function handleConversation($data){
        
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
                  $recent=Chats::where('id',$lastId)->first();
                 
                   event(new HookEvent($recent));
                   Log::info("rock");
                   Log::info($chj);
                }else{
                Log::info("Give ut");    
                Log::info($chj);
                }
                
                } 
}


public function handleReaction($data){
 
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


public function handleImage($data){
    
    if (count($data) !==0) {
        $chj=null;
     
            
                if ($data['key']['fromMe']) {
                   $chj= Chats::create([
                        'group_id'=>$data['key']['remoteJid']??'',
                        'message_id'=>$data['key']['id']??'',
                        'sender_name'=>'Zyler',
                        'message_type'=>$data['messageType'],
                        'body'=>$data['message']['imageMessage']['caption']??'',
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
                        'body'=>$data['message']['imageMessage']['caption']??'',
                        'is_from_me'=>$data['key']['fromMe']??'',
                        'source'=>$data['source']??'',
                         'reactionTo'=>null,
                        'participant_contact_id'=>$data['key']['participantAlt']??'',
                        'quoted_message_id' => $data['contextInfo']['stanzaId'] ?? null,                        'message_timestamp'=>Carbon::createFromTimestamp($data['messageTimestamp'])->toDateTimeString(),
                        'status'=>'DROP'
                   ]); 

                
        }
                   
                    if ($chj !== null) {
                    // 1. Extract the thumbnail array from your webhook payload
$thumbnailArray = $data['message']['imageMessage']['jpegThumbnail'] ?? [];

if (!empty($thumbnailArray)) {
    // 2. Pack the array integers into a binary string
    $binaryData = pack('C*', ...$thumbnailArray);

    // 3. Convert to a Base64 data URI string
    $thumbnailBase64 = 'data:image/jpeg;base64,' . base64_encode($binaryData);
}

                  $med= MediaDetails::create([
                    'base64'=>$data['message']['base64']??'',
                   'width'=>$data['message']['imageMessage']['width']??'',
                   'height'=>$data['message']['imageMessage']['height']??'',
                   'fileLength'=>$data['message']['imageMessage']['fileLength']['low']??'',
                   'mimetype'=>$data['message']['imageMessage']['mimetype']??'',
                   'group_id'=>$data['key']['remoteJid']??'',
                   'message_id'=>$data['key']['id']??'',
                   'type'=>$data['messageType'],
                   'thumbnailBase64'=>$thumbnailBase64
                   ]);
                    
                  $lastId= $chj->id;
                  $recent=Chats::where('id',$lastId)->first();
                 $lastMedId=$med->id;
                 $Media=MediaDetails::where('id',$lastMedId)->value('thumbnailBase64');

                 $recent->media = $Media;
                  event(new HookEvent($recent));

                   
    Log::info($recent);
    Log::info($data);
                }else{Log::info($chj);}
                
                }  


                }



                public function handleVoiceNote($data){
    
    if (count($data) !==0) {
        $chj=null;
     
            
                if ($data['key']['fromMe']) {
                   $chj= Chats::create([
                        'group_id'=>$data['key']['remoteJid']??'',
                        'message_id'=>$data['key']['id']??'',
                        'sender_name'=>'Zyler',
                        'message_type'=>$data['messageType'],
                        'body'=>$data['message']['imageMessage']['caption']??'',
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
                        'body'=>$data['message']['imageMessage']['caption']??'',
                        'is_from_me'=>$data['key']['fromMe']??'',
                        'source'=>$data['source']??'',
                         'reactionTo'=>null,
                        'participant_contact_id'=>$data['key']['participantAlt']??'',
                        'quoted_message_id' => $data['contextInfo']['stanzaId'] ?? null,                        'message_timestamp'=>Carbon::createFromTimestamp($data['messageTimestamp'])->toDateTimeString(),
                        'status'=>'DROP'
                   ]); 

                
        }
                   
                    if ($chj !== null) {

                  $med= MediaDetails::create([
                    'base64'=>$data['message']['base64']??'',
//'waveform'=>$data['message']['audioMessage']['waveform']??'',
                   'fileLength'=>$data['message']['audioMessage']['fileLength']['low']??'',
                   'mimetype'=>$data['message']['audioMessage']['mimetype']??'',
                   'group_id'=>$data['key']['remoteJid']??'',
                   'message_id'=>$data['key']['id']??'',
                   'type'=>$data['messageType'],
                   'seconds'=>$data['message']['audioMessage']['seconds']
                   ]);
                    
                  $lastId= $chj->id;
                  $recent=Chats::where('id',$lastId)->first();
                 $lastMedId=$med->id;
                 $Media=MediaDetails::where('id',$lastMedId)->value('seconds');

                 $recent->media = $Media;
                  event(new HookEvent($recent));

                   
    Log::info($recent);
    Log::info($data);
                }else{Log::info($chj);}
                
                }  


                }


                public function handleVideoMessage($data){
    
    if (count($data) !==0) {
        $chj=null;
     
            
                if ($data['key']['fromMe']) {
                   $chj= Chats::create([
                        'group_id'=>$data['key']['remoteJid']??'',
                        'message_id'=>$data['key']['id']??'',
                        'sender_name'=>'Zyler',
                        'message_type'=>$data['messageType'],
                        'body'=>$data['message']['imageMessage']['caption']??'',
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
                        'body'=>$data['message']['imageMessage']['caption']??'',
                        'is_from_me'=>$data['key']['fromMe']??'',
                        'source'=>$data['source']??'',
                         'reactionTo'=>null,
                        'participant_contact_id'=>$data['key']['participantAlt']??'',
                        'quoted_message_id' => $data['contextInfo']['stanzaId'] ?? null,                        'message_timestamp'=>Carbon::createFromTimestamp($data['messageTimestamp'])->toDateTimeString(),
                        'status'=>'DROP'
                   ]); 

                
        }
                   
                    if ($chj !== null) {

                  $med= MediaDetails::create([
                    'base64'=>$data['message']['base64']??'',
//'waveform'=>$data['message']['audioMessage']['waveform']??'',
                   'fileLength'=>$data['message']['audioMessage']['fileLength']['low']??'',
                   'mimetype'=>$data['message']['audioMessage']['mimetype']??'',
                   'group_id'=>$data['key']['remoteJid']??'',
                   'message_id'=>$data['key']['id']??'',
                   'type'=>$data['messageType'],
                   'seconds'=>$data['message']['audioMessage']['seconds']
                   ]);
                    
                  $lastId= $chj->id;
                  $recent=Chats::where('id',$lastId)->first();
                 $lastMedId=$med->id;
                 $Media=MediaDetails::where('id',$lastMedId)->value('seconds');

                 $recent->media = $Media;
                  event(new HookEvent($recent));

                   
    Log::info($recent);
    Log::info($data);
                }else{Log::info($chj);}
                
                }  


                }

}
