@extends('agent.layout')
@section('title','Dashboard')
<meta name="csrf-token" content="{{ csrf_token() }}"></meta>
@vite(['resources/js/Message/sendMessage.js','resources/js/Message/GetBroadCast.js'])
@section('content')

<?php
function getName($partId,$cons,$send_name){
        foreach ($cons as $con) {
           if ($con['contact_id']==$partId) {
                
                    return $con['name'];
                
           }  else{
            return $send_name;
           }

           
        }
}

function HandleReply($chats,$replyId){
        foreach ($chats as $ct) {
           if ($ct['message_id']==$replyId) {
             return '
              <div class="bg-orange-700/70 border-l-4 cursor-pointer border-orange-300 rounded-lg px-3 py-2 mb-2 replyMess" originalId="'.$replyId.'">
            <div class="text-xs font-semibold text-orange-200 mb-1">
                '.$ct['sender_name'].'
            </div>

            <div class="text-sm text-orange-50/90 break-words">
               '.$ct['body'].'...
            </div>
        </div>
             ';
           }
        }
}


?>


<div class="h-full overflow-hidden bg-slate-100">

    <div class="flex h-screen w-full">

        <!-- ================= SIDEBAR: GROUP LIST ================= -->
        <aside class="w-80 bg-white border-r border-slate-200 flex flex-col h-full z-10">
            
            <!-- Sidebar Header -->
            <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-orange-600 text-white flex items-center justify-center shadow-md shadow-orange-600/20">
                        <i class="fa-solid fa-comments text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 tracking-tight">Group Inbox</h2>
                        <p class="text-xs text-slate-500">160+ Groups Synced</p>
                    </div>
                </div>
                <!-- Agent Status Pill -->
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-1.5 h-1.5 mr-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                    Online
                </span>
            </div>

            <!-- Search Synced Groups -->
            <div class="p-3 border-b border-slate-100 bg-slate-50/50">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input type="text" placeholder="Search synced groups..." 
                        class="w-full pl-9 pr-3 py-2 bg-white border border-slate-200 rounded-xl text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all">
                </div>
            </div>

            <!-- Group Chat List Stream -->
            <div class="flex-1 overflow-y-auto divide-y divide-slate-100">
                
                <!-- Active Selected Group Item -->
                <div class="p-3.5 bg-orange-50/70 border-l-4 border-orange-600 cursor-pointer transition-all">
                    <div class="flex justify-between items-start mb-1">
                        <h3 class="text-xs font-bold text-slate-900 truncate max-w-[170px]">VIP Client Cohort A</h3>
                        <span class="text-[10px] text-orange-600 font-medium">10:42 AM</span>
                    </div>
                    <p class="text-xs text-slate-600 truncate"><strong>Jane Doe:</strong> Can someone share the pricing deck?</p>
                    <div class="flex items-center justify-between mt-2">
                        <span class="text-[10px] bg-slate-200/70 text-slate-600 px-2 py-0.5 rounded font-mono">1203630...g.us</span>
                        <span class="w-4 h-4 bg-orange-600 text-white rounded-full text-[10px] flex items-center justify-center font-bold">3</span>
                    </div>
                </div>

                <!-- Regular Group Item -->
                <div class="p-3.5 hover:bg-slate-50 cursor-pointer transition-all">
                    <div class="flex justify-between items-start mb-1">
                        <h3 class="text-xs font-semibold text-slate-800 truncate max-w-[170px]">Partners & Affiliates Q3</h3>
                        <span class="text-[10px] text-slate-400">Yesterday</span>
                    </div>
                    <p class="text-xs text-slate-500 truncate"><strong>Mark Smith:</strong> Done with verification.</p>
                    <div class="flex items-center justify-between mt-2">
                        <span class="text-[10px] bg-slate-100 text-slate-500 px-2 py-0.5 rounded font-mono">1203631...g.us</span>
                    </div>
                </div>

                <!-- Regular Group Item -->
                <div class="p-3.5 hover:bg-slate-50 cursor-pointer transition-all">
                    <div class="flex justify-between items-start mb-1">
                        <h3 class="text-xs font-semibold text-slate-800 truncate max-w-[170px]">Enterprise Onboarding</h3>
                        <span class="text-[10px] text-slate-400">Oct 20</span>
                    </div>
                    <p class="text-xs text-slate-500 truncate"><strong>You:</strong> Welcome to the channel everyone!</p>
                    <div class="flex items-center justify-between mt-2">
                        <span class="text-[10px] bg-slate-100 text-slate-500 px-2 py-0.5 rounded font-mono">1203632...g.us</span>
                    </div>
                </div>

            </div>

            <!-- Sidebar Footer Profile Summary -->
            <div class="p-3 border-t border-slate-200 bg-slate-50 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-slate-300 flex items-center justify-center font-bold text-xs text-slate-700">AG</div>
                    <div class="overflow-hidden">
                        <p class="text-xs font-bold text-slate-800 truncate">Agent Alex</p>
                        <p class="text-[10px] text-slate-500 truncate">business-phone instance</p>
                    </div>
                </div>
                <a href="#" class="text-slate-400 hover:text-orange-600 transition-colors" title="Settings">
                    <i class="fa-solid fa-gear text-sm"></i>
                </a>

                <div class="mt-4 border-t border-slate-800 pt-3">

        <form method="POST" action="{{ route('AgentLogout') }}">
            @csrf
        <button class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-400 transition hover:bg-red-500/10 hover:text-red-400">
            <span class="text-base">↪</span> Logout </button>
        </form>
    </div>

            </div>

        </aside>

        <!-- ================= MAIN CHAT WORKSPACE ================= -->
        <main class="flex-1 flex flex-col h-full bg-white">
            
            <!-- Chat Header -->
            <header class="px-6 py-3.5 border-b border-slate-200 flex items-center justify-between bg-white shadow-xs z-10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">VIP Client Cohort A</h2>
                        <p class="text-xs text-slate-500">48 participants &bull; Gateway Connected</p>
                    </div>
                </div>

                <!-- Quick Actions / Group Info Drawer toggle -->
                <div class="flex items-center gap-2">
                    <button class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition-all flex items-center gap-1.5">
                        <i class="fa-solid fa-shield-halved text-orange-600"></i>
                        <span>Supervisor Assist</span>
                    </button>
                    <button class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-all">
                        <i class="fa-solid fa-ellipsis-vertical text-xs"></i>
                    </button>
                </div>
            </header>

            <!-- Messages Stream Area -->
            <div id="MessageHolder" class="flex-1 overflow-y-auto p-6 space-y-4 bg-slate-50/50 h-10 overflow-y-scroll">
                
                <!-- Date Divider -->
                <div class="flex justify-center">
                    <span class="px-3 py-1 bg-slate-200/70 text-slate-600 text-[10px] font-semibold rounded-full uppercase tracking-wider">Today</span>
                </div>

                <!-- Inbound Message From Member -->
               
                @foreach ($ftChats as $chat)
               
                   

                @if ($chat['is_from_me'])
                <div class="flex items-end justify-end gap-2 mit{{ $chat['message_id'] }} chatM" id="{{ $chat['message_id'] }}" name="{{ $chat['sender_name'] }}" body="{{ $chat['body'] }}">
                      <div class="bg-orange-600 text-white rounded-2xl rounded-br-sm p-3.5 shadow-md shadow-orange-600/10 max-w-xl">
                     
                       @if ($chat['quoted_message_id'] !== NULL)
                             <!-- Original message being replied to -->
                                <?php echo HandleReply($ftChats, $chat['quoted_message_id'])?>
                        @endif
                        
                        
                        <div class="flex items-center justify-between gap-4 mb-1">
                            <span class="text-xs font-semibold text-orange-100">You (<?php echo getName($chat['participant_contact_id'],$contacts,$chat['sender_name'])?>)</span>
                            <span class="block text-[10px] text-white-400 text-right mt-1">
            {{ \Carbon\Carbon::parse($chat['message_timestamp'])->format('H:i:s') }}
        </span>
                        </div>
                        <p class="text-xs leading-relaxed">{{ $chat['body'] }}</p>
                        <div class="flex items-center justify-end gap-1 mt-1 text-[10px] text-orange-200">
                            
                            {{-- <i class="fa-solid fa-check-double text-emerald-300"></i> --}}
                        </div>
                    </div>
                </div>
                @else
              
<div class="flex items-end gap-2 max-w-xl mit{{ $chat['message_id'] }} chatM" id="{{ $chat['message_id'] }}"  name="{{ $chat['sender_name'] }}" body="{{ $chat['body'] }}">
    <div class="w-7 h-7 rounded-full bg-slate-300 text-[10px] font-bold text-slate-700 shrink-0 flex items-center justify-center">
        JD
    </div>

    <div class="bg-white border border-slate-200/80 rounded-2xl rounded-bl-sm p-3.5 shadow-sm max-w-full">

        <!-- Original message being replied to -->
       @if ($chat['quoted_message_id'] !== NULL)
                <?php echo HandleReply($ftChats, $chat['quoted_message_id'])?>
       @endif

        <!-- Reply header -->
        <div class="flex items-center gap-2 mb-1">
            <span class="text-xs font-bold text-orange-600">
                <?php echo getName($chat['participant_contact_id'], $contacts, $chat['sender_name']) ?>
            </span>
        </div>

        <!-- Actual reply -->
        <p class="text-xs text-slate-800 leading-relaxed whitespace-pre-wrap break-words">
            {{ $chat['body'] }}
        </p>

        <!-- Timestamp -->
        <span class="block text-[10px] text-slate-400 text-right mt-1">
            {{ \Carbon\Carbon::parse($chat['message_timestamp'])->format('H:i:s') }}
        </span>

    </div>
</div>

                @endif

                @endforeach
       
            </div>

            <div class="p-4 bg-white border-t border-slate-200">
                            <div id="compose-error-message" class="hidden rounded-lg border border-red-500/20 bg-red-500/10 px-4 py-3 text-sm text-red-400"></div>

                <form id="message_form" class="space-y-2">
                   
                    
                    <div class="flex items-center gap-2">
                        <!-- Attachment / Canned Response Buttons -->
                        <p  class="p-2.5 text-slate-400 hover:text-orange-600 hover:bg-slate-50 rounded-xl transition-all" title="Attach Document">
                            <i class="fa-solid fa-paperclip text-sm"></i>
                        </p>
                        <p  class="p-2.5 text-slate-400 hover:text-orange-600 hover:bg-slate-50 rounded-xl transition-all" title="Canned Responses">
                            <i class="fa-solid fa-bolt text-sm"></i>
                        </p>
                        
                      <!-- Reply Preview (Hidden by default) -->
                      <div class="flex-1 relative">
<div id="ReplyPreview" class="hidden mb-2 bg-slate-100 border-l-4 border-orange-500 rounded-lg p-3 relative">

    <div class="flex items-start justify-between gap-3">

        <div class="min-w-0 flex-1">
            <p class="text-[11px] font-bold text-orange-600 mb-1">
                Replying to John Doe
            </p>

            <p id="ReplyPreviewText" class="text-xs text-slate-600 truncate">
                This is the original message being replied to...
            </p>
        </div>

        <button type="button" id="CancelReply"
            class="w-7 h-7 flex items-center justify-center rounded-full hover:bg-slate-200 text-slate-500 hover:text-red-500 transition">
            &times;
        </button>

    </div>
</div>

<!-- Message Input Field -->
<div class="flex-1 relative">
    <input type="text" id="input_Mess" name="message_text" required
        placeholder="Type group message as agent..."
        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all">
</div>

</div>
                            <input type="hidden" id="GID" name="groupId" value="{{ $group_Id }}">
                            <input type="hidden" id="AID" name="user_id" value="{{ auth('agent')->user()['id']}}">
                        <!-- Send Button -->
                        <button type="submit" class="px-5 py-2.5 bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-xl shadow-lg shadow-orange-600/20 active:scale-[0.99] transition-all flex items-center gap-2 text-xs">
                            <span>Send</span>
                            <i class="fa-solid fa-paper-plane text-xs"></i>
                            <div id="MessSpin" class="hidden h-3 w-3 animate-spin rounded-full border-2 border-gray-300 border-t-orange-600"></div>
                        </button>
                    </div>

                    <!-- Safety Indicator Footnote -->
                   {{--  <div class="flex items-center justify-between px-1">
                        <p class="text-[10px] text-slate-400">
                            Anti-ban safeguards active &bull; Queue delay enabled (2-6s jitter)
                        </p>
                        <span class="text-[10px] text-slate-400 font-mono">UTF-8 Supported</span>
                    </div> --}}
                </form>
            </div>



<svg id="scrollToBottom" class="fixed bottom-24 right-6 z-50 flex items-center justify-center
       w-12 h-12 rounded-full
       bg-gradient-to-br from-orange-400 via-orange-500 to-amber-600
       text-white shadow-xl shadow-orange-500/40
       ring-1 ring-white/20 backdrop-blur-md
       hover:scale-110 hover:shadow-orange-400/60
       active:scale-95 transition-all duration-300
       animate-bounce" 
     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m-7-7 7 7 7-7"/>
</svg>
        </main>

    </div>

</div>



<script>
 
@endsection