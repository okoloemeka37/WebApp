@extends('agent.layout')
@section('title','Dashboard')
<meta name="csrf-token" content="{{ csrf_token() }}"></meta>
@vite(['resources/js/Message/sendMessage.js','resources/js/Message/GetBroadCast.js','resources/js/Message/Chats.js'])
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

      <php @include('agent.Chats.Aside')?></php>
        <!-- ================= MAIN CHAT WORKSPACE ================= -->
        <main class="flex-1 flex flex-col h-full bg-white">
            
            <!-- Chat Header -->
            <header class="px-6 py-3.5 border-b border-slate-200 flex items-center justify-between bg-white shadow-xs z-10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">{{ $activeGroup->subject ?? 'Unnamed group' }}</h2>
                        <p class="text-xs text-slate-500">{{ $activeGroup->size ?? 0 }} participants &bull; Gateway Connected</p>
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


<p class=" hidden topErr text-red-500 text-sm font-medium bg-red-50 border border-red-200 rounded-lg px-3 py-2">
    <i class="fa-solid fa-circle-exclamation mr-1"></i>
   <span class="pageError"></span>
</p>


            <!-- Messages Stream Area -->
 

<div id="MessageHolder"
    class="flex-1 min-h-0 overflow-y-auto px-3 sm:px-6 py-5 bg-[#f7f5f2] scroll-smooth">

    <!-- Date Divider -->
    <div class="flex justify-center mb-6">
        <span class="px-3 py-1 bg-white border border-slate-200/80 text-slate-400 text-[9px] font-bold rounded-full tracking-widest uppercase shadow-sm">
            Today
        </span>
    </div>

    @foreach ($ftChats as $chat)

        @if ($chat['is_from_me'])

        <!-- OUTGOING -->
        <div class="flex justify-end mit{{ $chat['message_id'] }} chatM mb-4"
            id="{{ $chat['message_id'] }}"
            name="{{ $chat['sender_name'] }}"
            body="{{ $chat['body'] }}"
            participantId="{{ $chat['participant_contact_id'] }}">

            <div class="max-w-[85%] sm:max-w-[65%] lg:max-w-[55%] min-w-0">

<div class="inline-block max-w-full bg-[#ea580c] text-white rounded-2xl rounded-tr-[5px] px-3 py-1.5 shadow-sm">

                    <!-- Sender -->
                    <div class="text-[10px] font-bold text-orange-100 mb-1">
                        You (<?php echo getName($chat['participant_contact_id'],$contacts,$chat['sender_name'])?>)
                    </div>

                    <!-- Quoted Reply -->
                    @if ($chat['quoted_message_id'] !== NULL)
                        <div class="mb-2 pl-2.5 pr-2 py-1.5 border-l-2 border-white/80 bg-black/15 rounded-r-md text-[11px] text-orange-50 leading-relaxed">
                            <?php echo HandleReply($ftChats, $chat['quoted_message_id'])?>
                        </div>
                    @endif

                      @if(count($chat['media'])!==0)
                      
                    @foreach($chat['media'] as $med)
                        <img width="100px" height="100px" id="{{ $chat['message_id'] }}" class="ChatImage" src="data:image/png;base64,{{ $med['base64'] }}" alt=""> 
                    @endforeach
                   
                    @endif

                    <!-- Message -->
                    <p class="text-[13px] leading-[1.6] whitespace-pre-wrap break-words">
                        {{ $chat['body'] }}
                    </p>

                    <!-- Time -->
                    <div class="flex justify-end mt-1">
                        <span class="text-[9px] text-orange-100/80">
                            {{ \Carbon\Carbon::parse($chat['message_timestamp'])->format('H:i') }}
                        </span>
                    </div>

                </div>

                <!-- Reactions -->
                @if (count($chat['reaction']) !== 0)
                    <div class="flex justify-end -mt-2 pr-2 relative z-10">
                        <div class="inline-flex items-center gap-1 bg-white border border-slate-200/80 rounded-full px-2.5 py-0.5 shadow-sm text-xs react{{ $chat['message_id'] }}">
                            @foreach ($chat['reaction'] as $react)
                                <span class="cursor-pointer hover:scale-125 transition-transform" id="reaction{{ $react['participant_contact_id'] }}">
                                    {{ $react['body'] }}
                                </span>
                            @endforeach
                            <span class="text-[9px] font-bold text-slate-500 pl-1 border-l border-slate-200">
                                {{ count($chat['reaction']) }}
                            </span>
                        </div>
                    </div>
                @endif

            </div>
        </div>

        @else

        <!-- INCOMING -->
       
        <div class="flex items-end gap-2 max-w-[90%] sm:max-w-[75%] mb-4 mit{{ $chat['message_id'] }} chatM"
            id="{{ $chat['message_id'] }}"
            name="{{ $chat['sender_name'] }}"
            body="{{ $chat['body'] }}"
            participantId="{{ $chat['participant_contact_id'] }}">

            <!-- Avatar -->
            <div class="w-7 h-7 mb-4 rounded-full bg-orange-100 text-orange-600 border border-orange-200/70 text-[10px] font-bold shrink-0 flex items-center justify-center uppercase">
                {{ strtoupper(substr($chat['sender_name'] ?? 'U', 0, 2)) }}
            </div>

             @if ($chat['message_type']=='audioMessage')
             
            <div class="flex items-center mb-1 gap-3 w-64 rounded-xl bg-neutral-800 p-2.5 text-white">

    <button data-audio="{{ $chat['media'][0]['base64'] }}"  onclick="VoiceNote(this)"  class="playVN w-9 h-9 shrink-0 rounded-full bg-orange-600 flex items-center justify-center">
        <i class="fa-solid fa-play text-sm"></i>
    </button>

    <div class="flex-1 min-w-0">
        <div class="flex items-center gap-[3px] h-6 freqHolder">
            <span class="w-1 h-3 bg-nuetral-500 rounded-full"></span>
            <span class="w-1 h-5 bg-nuetral-500 rounded-full"></span>
            <span class="w-1 h-4 bg-nuetral-500 rounded-full"></span>
            <span class="w-1 h-6 bg-nuetral-500 rounded-full"></span>
            <span class="w-1 h-3 bg-nuetral-500 rounded-full"></span>
            <span class="w-1 h-5 bg-neutral-500 rounded-full"></span>
            <span class="w-1 h-4 bg-neutral-500 rounded-full"></span>
         
        </div>

        <div class="flex justify-between text-[10px] text-neutral-400 mt-1">
            <span class="timer">0:00</span>
            <span>{{ $chat['media'][0]['seconds'] }}secs</span>
        </div>
    </div>
<p class="VnSpeaker"><i class="fa-solid fa-volume-xmark text-neutral-400"></i></p>
    
            </div>
        @else
            <div class="min-w-0">

<div class="inline-block max-w-full bg-white border border-slate-200/80 text-slate-800 rounded-2xl rounded-bl-[5px] px-3 py-1.5 shadow-sm">

                    <!-- Sender -->
                    <div class="text-[10px] font-bold text-orange-600 mb-1">
                        <?php echo getName($chat['participant_contact_id'], $contacts, $chat['sender_name']) ?>
                    </div>

                    <!-- Quoted Reply -->
                    @if ($chat['quoted_message_id'] !== NULL)
                        <div class="mb-2 pl-2.5 pr-2 py-1.5 border-l-2 border-orange-500 bg-orange-50 rounded-r-md text-[11px] text-slate-600 leading-relaxed">
                            <?php echo HandleReply($ftChats, $chat['quoted_message_id'])?>
                        </div>
                    @endif
                    @if(count($chat['media'])!==0)
                      
                    @foreach($chat['media'] as $med)
                        <img width="100px" height="100px"  id="{{ $chat['message_id'] }}" class="ChatImage" src="data:image/png;base64,{{ $med['base64'] }}" alt=""> 
                    @endforeach
                   
                    @endif
                    <!-- Message -->
                    <p class="text-[13px] leading-[1.6] whitespace-pre-wrap break-words">
                        {{ $chat['body'] }}
                    </p>

                    <!-- Time -->
                    <div class="flex justify-end mt-1">
                        <span class="text-[9px] text-slate-400">
                            {{ \Carbon\Carbon::parse($chat['message_timestamp'])->format('H:i') }}
                        </span>
                    </div>

                </div>

                <!-- Reactions -->
                @if (count($chat['reaction']) !== 0)
                    <div class="flex justify-start -mt-2 pl-2 relative z-10">
                        <div class="inline-flex items-center gap-1 bg-white border border-slate-200/80 rounded-full px-2.5 py-0.5 shadow-sm text-xs react{{ $chat['message_id'] }}">
                            @foreach ($chat['reaction'] as $react)
                                <span class="cursor-pointer hover:scale-125 transition-transform" id="reaction{{ $react['participant_contact_id'] }}">
                                    {{ $react['body'] }}
                                </span>
                            @endforeach
                            <span class="text-[9px] font-bold text-slate-500 pl-1 border-l border-slate-200">
                                {{ count($chat['reaction']) }}
                            </span>
                        </div>
                    </div>
                @endif

            </div>
            @endif
        </div>

        @endif

    @endforeach

    <div class="h-3"></div>
</div>






            <div class="p-4 bg-white border-t border-slate-200">
                            <div id="compose-error-message" class="hidden rounded-lg border border-red-500/20 bg-red-500/10 px-4 py-3 text-sm text-red-400"></div>

                <form id="message_form" class="space-y-2">
                   
                    
                    <div class="flex items-center gap-2">
                        <!-- Attachment / Canned Response Buttons -->
                        <button type="button" id="attachDocument" class="shrink-0 w-9 h-9 flex items-center justify-center text-slate-400 hover:text-orange-600 hover:bg-slate-50 rounded-xl transition-all" title="Attach Document" aria-label="Attach Document">
                            <i class="fa-solid fa-paperclip text-sm"></i>
                        </button>
                        <input type="file" id="attachDocumentFile" class="hidden" name="attachment">
                        <button type="button" id="cannedResponses" class="shrink-0 w-9 h-9 flex items-center justify-center text-slate-400 hover:text-orange-600 hover:bg-slate-50 rounded-xl transition-all" title="Canned Responses" aria-label="Canned Responses">
                            <i class="fa-solid fa-bolt text-sm"></i>
                        </button>
                        
                      <!-- Reply Preview (Hidden by default) -->
                      <div class="flex-1 relative">
<div id="ReplyPreview" class="hidden mb-2 bg-slate-100 border-l-4 border-orange-500 rounded-lg p-3 relative">

    <div class="flex items-start justify-between gap-3">

        <div class="min-w-0 flex-1">
            <p class="text-[11px] font-bold text-orange-600 mb-1 reply-name"></p>

            <p id="ReplyPreviewText" class="text-xs text-slate-600 truncate reply-body"></p>
        </div>

        <p id="CancelReply"
            class="w-7 h-7 flex items-center justify-center rounded-full hover:bg-slate-200 text-slate-500 hover:text-red-500 transition">
            &times;
        </p>

    </div>
</div>

<!-- Message Input Field -->
<div class="flex-1 relative">
    <textarea id="input_Mess" name="message_text" required rows="1"
        placeholder="Type group message as agent..."
        class="block w-full max-h-32 resize-none overflow-y-auto px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 placeholder-slate-400 leading-5 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all"></textarea>
</div>

</div>
                            <input type="hidden" id="GID" name="groupId" value="{{ $group_Id }}">
                            <input type="hidden" id="AID" name="user_id" value="{{ auth('agent')->user()['id']}}">
                           
                            <!-- Send Button -->
                        <button type="submit" class="shrink-0 px-4 sm:px-5 py-2.5 bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-xl shadow-lg shadow-orange-600/20 active:scale-[0.99] transition-all flex items-center gap-2 text-xs" aria-label="Send message">
                            <span class="hidden sm:inline">Send</span>
                            <i class="fa-solid fa-paper-plane text-sm"></i>
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



<div id="imageModal" class="fixed hidden inset-0 z-50  items-center justify-center bg-orange-950/80 backdrop-blur-sm p-4">

    <div class="relative w-full max-w-4xl overflow-hidden rounded-2xl bg-white border border-orange-200 shadow-2xl shadow-orange-950/30">

        <!-- Header -->
        <div class="flex items-center justify-between px-5 py-4 border-b border-orange-100 bg-orange-50">
            <div>
                <h3 class="text-sm font-bold text-orange-950">Image Preview</h3>
                <p class="text-xs text-orange-600 mt-1">Preview attachment</p>
            </div>

            <button  class="closeImageModal flex items-center justify-center w-9 h-9 rounded-full bg-orange-100 hover:bg-orange-200 text-orange-700 hover:text-orange-950 transition">
                &times;
            </button>
        </div>

        <!-- Image -->
        <div class="flex items-center justify-center bg-orange-50/50 p-4 sm:p-8 min-h-[300px] max-h-[75vh]">
            <img
                src="your-image.jpg"
                alt="Preview"
                class="preview max-w-full max-h-[65vh] object-contain rounded-xl shadow-lg"
            >
        </div>

        <!-- Footer -->
        <div class="flex items-center justify-between px-5 py-3 border-t border-orange-100 bg-white">
            <span class="text-xs font-medium text-orange-700">Image attachment</span>

            <button class="px-4 py-2 rounded-lg bg-orange-600 hover:bg-orange-700 text-white text-sm font-semibold shadow-md shadow-orange-600/20 transition">
                Download Image
            </button>
        </div>

    </div>
</div>


        </main>

    </div>

</div>



<script type="module">
    //handle Image view
    const chats=@json($ftChats);

    function HandleImageView(){
        const ChatImage=document.querySelectorAll(".ChatImage");

        ChatImage.forEach(img => {
            img.addEventListener('click',()=>{
                const message_id=img.id;
                console.log(message_id);
               const [chatDetails]=chats.filter((val)=>val['message_id']==message_id);

               const imageModal=document.querySelector("#imageModal");
               imageModal.classList.remove('hidden');
               const mediaDetails=chatDetails['media'][0]
               imageModal.querySelector(".preview").src="data:image/png;base64,"+mediaDetails['base64']

               
            })
        });

    }

    HandleImageView()

    const closeImageModal=document.querySelector(".closeImageModal")
    closeImageModal.addEventListener('click',()=>{
         const imageModal=document.querySelector("#imageModal");
               imageModal.classList.add('hidden');
    })
   
    


document.getElementById('sidebarGroupSearch')?.addEventListener('input', function () {
    const search = this.value.toLowerCase().trim();

    document.querySelectorAll('.group-sidebar-item').forEach(function (item) {
        item.classList.toggle('hidden', !item.dataset.groupName.includes(search));
    });
});

</script>
 
@endsection