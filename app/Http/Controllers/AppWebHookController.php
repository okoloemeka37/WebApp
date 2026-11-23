<?php

namespace App\Http\Controllers;
use App\Events\HookEvent;
use App\Jobs\processIncomingMessage;
use App\Models\chat\MediaDetails;
use App\Models\chat\Reaction;
use App\Models\Chats;

use App\Models\Contacts;
use App\Models\Participant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Log;
use App\Services\WhatsAppGroupService;

class AppWebHookController extends Controller
{

protected WhatsAppGroupService $whatsappService;

function __construct(WhatsAppGroupService $whatsappService){
        $this->whatsappService=$whatsappService;
}


    public function handle(Request $request)
    {
       
        // 1. Capture the raw event payload coming from the Docker container
        $event = $request->input('event');
        $data = $request->input('data');

      
        // 2. Handle different event types emitted by Evolution API
    if (array_key_exists('key',$data)) {
           
        if (count($data) !==0) {
            if (str_ends_with($data['key']['remoteJid'],'@g.us')) {

           Log::info('WhatsApp Webhook Received: ' . $event);
        
      
        
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

        processIncomingMessage::dispatch($data);
        
    }




}


