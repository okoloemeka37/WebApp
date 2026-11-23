<?php

namespace App\Http\Controllers;

use App\Models\chat\MediaDetails;
use App\Models\chat\Reaction;
use App\Models\Chats;
use App\Models\Contacts;
use App\Models\Participant;
use App\Models\Group;
use App\Services\WhatsAppGroupService;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
class GroupController extends Controller
{
    
protected WhatsAppGroupService $whatsappService;

function __construct(WhatsAppGroupService $whatsappService){
        $this->whatsappService=$whatsappService;
}


    public function groupChat($id){
        try {
            $group_Id= \decrypt($id);

            /*  $participants=$this->whatsappService->fetchGroupInfo($group_Id)['participants'];
              foreach ($participants as $pat) {
                Participant::updateOrCreate(['group_id'=>$group_Id,'phoneNumber'=>$pat['phoneNumber']],[
                    'isAdmin'=>$pat['admin']=='null'?'false':'true',
                    'phoneNumber'=>$pat['phoneNumber'],
                    'participant_id'=>$pat['id'],
                    'group_id'=>$group_Id
                    ]);
            }

            $messages=$this->whatsappService->getGroupMessages($group_Id);            

         $chats=$messages['messages']['records'];

         foreach ($chats as $chat) {
            if ($chat['messageType']=='conversation') {
                

           $reactions = Reaction::where('group_id', $group_Id)->whereNot('body',NULL)->get()->toArray();
$ftChats = Chats::where('group_id', $group_Id)->orderBy('message_timestamp')->orderBy('id')->get();

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
            'source'=>$chat['source']??''
            
            ]);
            
         
            }
           
            } 


            dd($participants); */
     
            $reactions = Reaction::where('group_id', $group_Id)->whereNot('body',NULL)->get()->toArray();
            $media=MediaDetails::where('group_id', $group_Id)->get()->toArray();
            
        $ftChats=Chats::where('group_id',$group_Id)->get();

$reactionGroups = [];

foreach ($reactions as $reaction) {
    $reactionGroups[$reaction['reactionTo']][] = $reaction;
}

$mediaGroups = [];

foreach ($media as $me) {
    $mediaGroups[$me['message_id']][] = $me;
}

foreach ($ftChats as $key => $chat) {
    $messageId = $chat['message_id'];

    $ftChats[$key]['reaction'] = $reactionGroups[$messageId] ?? [];
    $ftChats[$key]['media'] = $mediaGroups[$messageId] ?? [];
}



          $contacts=Contacts::get();

        $activeGroup = Group::where('group_id', $group_Id)->first();
        $groups = Group::orderByDesc('subjectTime')->get()->map(function ($group) {
            $latestChat = Chats::where('group_id', $group->group_id)
                ->orderByDesc('message_timestamp')
                ->orderByDesc('id')
                ->first();

            $group->latest_chat = $latestChat;
            return $group;
        })->sortByDesc(function ($group) {
            return $group->latest_chat?->message_timestamp
                ?? $group->subjectTime
                ?? $group->updated_at;
        })->values();

        return view('Agent/Chats',compact('ftChats','contacts','group_Id','activeGroup','groups'));  
         
            } catch (\Throwable $th) {

          $groupInfo=Group::where('group_id',$group_Id)->first();
          $partcipants=Participant::where('group_id',$group_Id)->get();


//getting side chats

//$sideGroup=DB::select('select *')

        return view('Agent/Chats',compact('ftChats','contacts','group_Id','groupInfo','partcipants'));  


        } catch (\Throwable $th) {

           \Log::info($th);
        }

    }


    
public function AddGroups()
{
    $groups = $this->whatsappService->fetchAllGroups();

    $data = [];

    foreach ($groups as $group) {
        $data[] = [
            'group_id' => $group['id'],
            'linkedParent' => $group['linkedParent'] ?? '',
            'isCommunityAnnounce' => $group['isCommunityAnnounce'] ?? '',
            'isCommunity' => $group['isCommunity'] ?? '',
            'announce' => $group['announce'] ?? '',
            'restrict' => $group['restrict'] ?? '',
            'descId' => $group['descId'] ?? '',
            'desc' => $group['desc'] ?? '',
            'owner' => $group['owner'] ?? '',
            'group_creation_time' => !empty($group['creation'])
                ? Carbon::createFromTimestamp($group['creation'])->toDateTimeString()
                : null,
            'size' => $group['size'] ?? '',
            'pictureUrl' => $group['pictureUrl'] ?? '',
            'subjectTime' => !empty($group['subjectTime'])
                ? Carbon::createFromTimestamp($group['subjectTime'])->toDateTimeString()
                : null,
            'subjectOwner' => $group['subjectOwner'] ?? '',
            'subject' => $group['subject'] ?? '',
        ];
    }

    if (!empty($data)) {
        Group::upsert(
            $data,
            ['group_id'],
            [
                'linkedParent',
                'isCommunityAnnounce',
                'isCommunity',
                'announce',
                'restrict',
                'descId',
                'desc',
                'owner',
                'group_creation_time',
                'size',
                'pictureUrl',
                'subjectTime',
                'subjectOwner',
                'subject',
            ]
        );
    }

    return count($data);
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



    public function getThumbNailedImage(Request $request){
        
try {

    $message_id=$request['id'];
    
    $media=MediaDetails::where('message_id',$message_id)->first();
    
    if ($media === null) {
    return response()->json([
        'success' => false,
        'message' => 'Media not found'
    ], 404);
}else{    return response()->json(['success' => true,'src'=>$media],200);
}

} catch (\Throwable $e) {
              return response()->json([
        'success' => false,
        'message'=>$e->getMessage(),
        'error' => ['message' => $e->getMessage(),]
    ], 500);
       }
    }


     public function getVoiceNote(Request $request){
        
try {

    $message_id=$request['id'];
    
    $media=MediaDetails::where('message_id',$message_id)->first();
    
    if ($media === null) {
    return response()->json([
        'success' => false,
        'message' => 'Media not found'
    ], 404);
}else{    return response()->json(['success' => true,'src'=>$media],200);
}

} catch (\Throwable $e) {
              return response()->json([
        'success' => false,
        'message'=>$e->getMessage(),
        'error' => ['message' => $e->getMessage(),]
    ], 500);
       }
    }


}
