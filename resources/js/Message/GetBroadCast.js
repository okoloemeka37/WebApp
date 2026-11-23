import VoiceNote from "./Chats";


let ChatId=[];
function srt(){
    const allChats=document.querySelectorAll('.chatM');

allChats.forEach(ct => {
    let snip={id:ct['id'],name:ct.getAttribute('name'),'body':ct.getAttribute('body')}
    ChatId.push(snip);
});
}

srt()

function updateSidebarPreview(mess) {
    if (!mess?.group_id) return;

    const item = document.querySelector(`.group-sidebar-item[data-group-id="${CSS.escape(mess.group_id)}"]`);
    if (!item) return;

    const preview = item.querySelector('p');
    const time = item.querySelector('.sidebar-time');
    const sender = mess.is_from_me == 1 ? 'You' : (mess.sender_name || 'Unknown');
    const body = mess.body || '[Media/message]';

    if (preview) preview.innerHTML = `<strong>${sender}:</strong> ${body}`;

    if (time) {
        const timestamp = mess.message_timestamp ? new Date(mess.message_timestamp) : new Date();
        const validTimestamp = Number.isNaN(timestamp.getTime()) ? new Date() : timestamp;
        const today = new Date();
        const yesterday = new Date(today);
        yesterday.setDate(today.getDate() - 1);

        if (validTimestamp.toDateString() === today.toDateString()) {
            time.textContent = validTimestamp.toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });
        } else if (validTimestamp.toDateString() === yesterday.toDateString()) {
            time.textContent = 'Yesterday';
        } else {
            time.textContent = validTimestamp.toLocaleDateString([], { month: 'short', day: 'numeric' });
        }
        time.title = validTimestamp.toLocaleString([], {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: 'numeric',
            minute: '2-digit'
        });
    }

    item.parentElement?.prepend(item);
}

function HandleReply(replyId) {
    if (replyId === null) return '';

    const item = ChatId.find(item => item['id'] == replyId);

    if (!item){return ''};

 
    return `
        <div class="bg-orange-700/70 border-l-4 cursor-pointer border-orange-300 rounded-lg px-3 py-2 mb-2 replyMess" originalId="${replyId}">
            <div class="text-xs font-semibold text-orange-200 mb-1">
                ${item['name']}
            </div>

            <div class="text-sm text-orange-50/90 break-words">
                ${item['body']}
            </div>
        </div>
    `;
}    


       document.addEventListener('DOMContentLoaded', () => {


            window.Echo.channel('hooks')
                .listen('.hook.updated', (event) => {
                  
                    //document.getElementById('messages').textContent =  event.message;
                    
                     
                    if(event.message.length > 0){

                        const messa=JSON.parse(event.message)
                        messa.forEach(updateSidebarPreview);
                        
                        const mess=JSON.parse(event.message)

                       
                        const GID=document.querySelector('#GID').value;
                     console.log(GID)
                     console.log(mess['group_id'])
                     console.log(event.message)
                        if (GID==mess['group_id']) {

                                const dtr=mess['message_type']
                               // 
                               switch (dtr) {
                                case 'reactionMessage':
                                    console.log(mess)
                                ReactHandler(mess)
                                    break;
                               
                                    case 'conversation':
                                    converHandler(mess,'yh');
                                    console.log(mess)
                                 break

                                  case 'imageMessage':
                                     imageHandler(mess); 
                                break; 

                                 case 'audioMessage':
                                     audioHandler(mess); 
                                break; 
                                 case 'videoMessage':
                                     videoHandler(mess); 
                                break; 
                                
                                default:
                                    break;
                               }
                                

                               
                            
                        }
                        
                    }else{console.log("molp")}


                    replyMe()
                    thumbDownloadFunc()
                    //document.getElementById('connection-status').textContent ='Live update received!';
                });
        });

        
    
// Function to handle conversation

function converHandler(mess) {
                          const MessageHolder = document.querySelector("#MessageHolder");

    
                                let snip={id:mess['message_id'],name:mess['send_name'],'body':mess['body']}
                                ChatId.push(snip);
                                 const safeMessage = document.createElement("p");
                                    safeMessage.className = "text-xs leading-relaxed";
                                    safeMessage.textContent = mess['body'];
                                    
                                   

                                    const Message_Temp =mess['is_from_me']==1? 
`
<div class="flex justify-end items-end mit${mess['message_id']} chatM mb-3"
    id="${mess['message_id']}"
    name="${mess['sender_name']}"
    body="${mess['body']}"
    participantId="${mess['participant_contact_id']}">

    <div class="max-w-[85%] sm:max-w-[65%] lg:max-w-[55%] min-w-0">

        <div class="inline-block max-w-full bg-[#ea580c] text-white rounded-2xl rounded-tr-[5px] px-3 py-1.5 shadow-sm relative">

            <!-- Original message being replied to -->
            <div class="reply-content"></div>

            <!-- Sender -->
            <div class="text-[10px] font-bold text-orange-100 mb-0.5">
                You (Zyler)
            </div>

            <!-- Message -->
            <div class="message-content text-[13px] leading-[1.45] whitespace-pre-wrap break-words"></div>

            <!-- Timestamp -->
            <div class="flex justify-end mt-1">
                <span class="text-[9px] text-orange-100/80">
                    ${new Date().toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'})}
                </span>
            </div>

        </div>

        <!-- Reactions -->
        <div class="flex justify-end -mt-2 pr-2 relative z-10">
            <div class="inline-flex items-center gap-1 bg-white border border-slate-200 rounded-full px-2.5 py-0.5 shadow-sm text-xs react${mess['message_id']}">
                <span class="text-[10px] font-bold text-slate-500 pl-0.5 react${mess['message_id']}">0</span>
            </div>
        </div>

    </div>
</div>
`
:
`
<div class="flex items-end gap-2 max-w-[90%] sm:max-w-[75%] mb-3 mit${mess['message_id']} chatM"
    id="${mess['message_id']}"
    name="${mess['sender_name']}"
    body="${mess['body']}"
    participantId="${mess['participant_contact_id']}">

    <!-- Avatar -->
    <div class="w-7 h-7 mb-4 rounded-full bg-orange-100 border border-orange-200/70 flex items-center justify-center text-[10px] font-bold text-orange-600 shrink-0">
        JD
    </div>

    <div class="min-w-0">

        <div class="inline-block max-w-full bg-white border border-slate-200/80 rounded-2xl rounded-bl-[5px] px-3 py-1.5 shadow-sm relative">

            <!-- Original message being replied to -->
            <div class="reply-content"></div>

                                                    <!-- Sender -->
                                                    <div class="text-[10px] font-bold text-orange-600 mb-0.5">
                                                        ${mess['sender_name']}
                                                    </div>

                                                    <!-- Message -->
                                                    <div class="message-content text-[13px] leading-[1.45] text-slate-700 whitespace-pre-wrap break-words"></div>

                                                    <!-- Timestamp -->
                                                    <div class="flex justify-end mt-1">
                                                        <span class="text-[9px] text-slate-400">
                                                            ${new Date().toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'})}
                                                        </span>
                                                    </div>

                                                </div>

                                                <!-- Reactions -->
                                                <div class="flex justify-start -mt-2 pl-2 relative z-10">
                                                    <div class="inline-flex items-center gap-1 bg-white border border-slate-200 rounded-full px-2.5 py-0.5 shadow-sm text-xs react${mess['message_id']}">
                                                        <span class="text-[10px] font-bold text-slate-500 pl-0.5 react${mess['message_id']}">0</span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        `
                                        ;

                                    MessageHolder.insertAdjacentHTML('beforeend', Message_Temp);

                                    MessageHolder.lastElementChild
                                        .querySelector('.message-content')
                                        .replaceWith(safeMessage);
                                        
                                       
                                         const safereply = document.createElement("div");
                                         safereply.className = "text-xs leading-relaxed";
                                            safereply.innerHTML = HandleReply(mess['quoted_message_id']);
                                        MessageHolder.lastElementChild.querySelector('.reply-content').replaceWith(safereply);
                                
                                    MessageHolder.scrollTop = MessageHolder.scrollHeight;
                            
}

function ReactHandler(mess){
                          const MessageHolder = document.querySelector("#MessageHolder");

    const cla=".react"+mess['reactionTo']
    const holder=document.querySelector(cla);

    if (mess['body']===null) {
      
      document.getElementById("reaction" + mess['participant_contact_id'])?.remove();

      //document.querySelector(".react"+mess['reactionTo']).innerHTML= Number(document.querySelector(".react"+mess['reactionTo']).innerHTML)-1
    }else{
       
    const temp=`<span class="cursor-pointer" id="reaction${mess['participant_contact_id']}">${mess['body']}</span>`

    holder.insertAdjacentHTML('beforeend',temp)

    }
}


function imageHandler(mess){
                          const MessageHolder = document.querySelector("#MessageHolder");

                 let snip={id:mess['message_id'],name:mess['send_name'],'body':mess['body']}
                                ChatId.push(snip);
                                 const safeMessage = document.createElement("p");
                                    safeMessage.className = "text-xs leading-relaxed";
                                    safeMessage.textContent = mess['body'];
                                    
                                   

                                    const Message_Temp =mess['is_from_me']==1? 
`
<div class="flex justify-end items-end mit${mess['message_id']} chatM mb-3"
    id="${mess['message_id']}"
    name="${mess['sender_name']}"
    body="${mess['body']}"
    participantId="${mess['participant_contact_id']}">

    <div class="max-w-[85%] sm:max-w-[65%] lg:max-w-[55%] min-w-0">

        <div class="inline-block max-w-full bg-[#ea580c] text-white rounded-2xl rounded-tr-[5px] px-3 py-1.5 shadow-sm relative">

            <!-- Original message being replied to -->
            <div class="reply-content"></div>

            <!-- Sender -->
            <div class="text-[10px] font-bold text-orange-100 mb-0.5">
                You (Zyler)
            </div>
                                                        ${(mess['media'].length !== 0)
                                                        ? `<div class="relative w-[100px] h-[100px] group">
    <img 
        width="100px" 
        height="100px" 
        src="${mess['media']}" 
        alt=""
        class="w-full h-full object-cover rounded-lg"
    >

    <p class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg"
        title="Download image"
    >
        <i class="fa-solid fa-download text-white text-xl"></i>
    </p>
</div>
                                                        `: ''}
            <!-- Message -->
            <div class="message-content text-[13px] leading-[1.45] whitespace-pre-wrap break-words"></div>

            <!-- Timestamp -->
            <div class="flex justify-end mt-1">
                <span class="text-[9px] text-orange-100/80">
                    ${new Date().toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'})}
                </span>
            </div>

        </div>

        <!-- Reactions -->
        <div class="flex justify-end -mt-2 pr-2 relative z-10">
            <div class="inline-flex items-center gap-1 bg-white border border-slate-200 rounded-full px-2.5 py-0.5 shadow-sm text-xs react${mess['message_id']}">
                <span class="text-[10px] font-bold text-slate-500 pl-0.5 react${mess['message_id']}">0</span>
            </div>
        </div>

    </div>
</div>
`
:
`
<div class="flex items-end gap-2 max-w-[90%] sm:max-w-[75%] mb-3 mit${mess['message_id']} chatM"
    id="${mess['message_id']}"
    name="${mess['sender_name']}"
    body="${mess['body']}"
    participantId="${mess['participant_contact_id']}">

    <!-- Avatar -->
    <div class="w-7 h-7 mb-4 rounded-full bg-orange-100 border border-orange-200/70 flex items-center justify-center text-[10px] font-bold text-orange-600 shrink-0">
        JD
    </div>

    <div class="min-w-0">

        <div class="inline-block max-w-full bg-white border border-slate-200/80 rounded-2xl rounded-bl-[5px] px-3 py-1.5 shadow-sm relative">

            <!-- Original message being replied to -->
            <div class="reply-content"></div>

                                                    <!-- Sender -->
                                                    <div class="text-[10px] font-bold text-orange-600 mb-0.5">
                                                        ${mess['sender_name']}
                                                    </div>
                                                      ${(mess['media'].length !== 0)
                                                        ? `<div class="relative w-[100px] h-[100px] group">
    <img 
        width="100px" 
        height="100px" 
        id="thu${mess['message_id']}"
        src="${mess['media']}" 
        alt=""
        class="w-full h-full object-cover rounded-lg"
    >

    <p id="${mess['message_id']}" class="thumbDownload absolute inset-0 flex items-center justify-center bg-black/40 opacity-100 rounded-lg"
        title="Download image"
    >
        <i class="fa-solid fa-download text-white text-xl"></i>
    </p>
</div>
                                                        `: ''}
                                                    <!-- Message -->
                                                    <div class="message-content text-[13px] leading-[1.45] text-slate-700 whitespace-pre-wrap break-words"></div>
                                                   
                                                    <!-- Timestamp -->
                                                    <div class="flex justify-end mt-1">
                                                        <span class="text-[9px] text-slate-400">
                                                            ${new Date().toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'})}
                                                        </span>
                                                    </div>

                                                </div>

                                                <!-- Reactions -->
                                                <div class="flex justify-start -mt-2 pl-2 relative z-10">
                                                    <div class="inline-flex items-center gap-1 bg-white border border-slate-200 rounded-full px-2.5 py-0.5 shadow-sm text-xs react${mess['message_id']}">
                                                        <span class="text-[10px] font-bold text-slate-500 pl-0.5 react${mess['message_id']}">0</span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        `
                                        ;

                                    MessageHolder.insertAdjacentHTML('beforeend', Message_Temp);

                                    MessageHolder.lastElementChild
                                        .querySelector('.message-content')
                                        .replaceWith(safeMessage);
                                        
                                       
                                         const safereply = document.createElement("div");
                                         safereply.className = "text-xs leading-relaxed";
                                            safereply.innerHTML = HandleReply(mess['quoted_message_id']);
                                        MessageHolder.lastElementChild.querySelector('.reply-content').replaceWith(safereply);
                                
                                    MessageHolder.scrollTop = MessageHolder.scrollHeight;
                            
}

function audioHandler(mess){
              const MessageHolder = document.querySelector("#MessageHolder");

                 let snip={id:mess['message_id'],name:mess['send_name'],'body':mess['body']}
                                ChatId.push(snip);
                                 const safeMessage = document.createElement("p");
                                    safeMessage.className = "text-xs leading-relaxed";
                                    safeMessage.textContent = mess['body'];
                                    
                                   

                                    const Message_Temp =mess['is_from_me']==1? 
`
<div class="flex justify-end items-end mit${mess['message_id']} chatM mb-3"
    id="${mess['message_id']}"
    name="${mess['sender_name']}"
    body="${mess['body']}"
    participantId="${mess['participant_contact_id']}">

    <div class="max-w-[85%] sm:max-w-[65%] lg:max-w-[55%] min-w-0">

        <div class="inline-block max-w-full bg-[#ea580c] text-white rounded-2xl rounded-tr-[5px] px-3 py-1.5 shadow-sm relative">

            <!-- Original message being replied to -->
            <div class="reply-content"></div>

            <!-- Sender -->
            <div class="text-[10px] font-bold text-orange-100 mb-0.5">
                You (Zyler)
            </div>
                                                        ${(mess['media'].length !== 0)
                                                        ? `<div class="relative w-[100px] h-[100px] group">
    <img 
        width="100px" 
        height="100px" 
        src="${mess['media']}" 
        alt=""
        class="w-full h-full object-cover rounded-lg"
    >

    <p class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg"
        title="Download image"
    >
        <i class="fa-solid fa-download text-white text-xl"></i>
    </p>
</div>
                                                        `: ''}
            <!-- Message -->
            <div class="message-content text-[13px] leading-[1.45] whitespace-pre-wrap break-words"></div>

            <!-- Timestamp -->
            <div class="flex justify-end mt-1">
                <span class="text-[9px] text-orange-100/80">
                    ${new Date().toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'})}
                </span>
            </div>

        </div>

        <!-- Reactions -->
        <div class="flex justify-end -mt-2 pr-2 relative z-10">
            <div class="inline-flex items-center gap-1 bg-white border border-slate-200 rounded-full px-2.5 py-0.5 shadow-sm text-xs react${mess['message_id']}">
                <span class="text-[10px] font-bold text-slate-500 pl-0.5 react${mess['message_id']}">0</span>
            </div>
        </div>

    </div>
</div>
`
:
`
<div class="flex items-end gap-2 max-w-[90%] sm:max-w-[75%] mb-3 mit${mess['message_id']} chatM"
    id="${mess['message_id']}"
    name="${mess['sender_name']}"
    body="${mess['body']}"
    participantId="${mess['participant_contact_id']}">

    <!-- Avatar -->
    <div class="w-7 h-7 mb-4 rounded-full bg-orange-100 border border-orange-200/70 flex items-center justify-center text-[10px] font-bold text-orange-600 shrink-0">
        JD
    </div>

    <div class="min-w-0">

        <div class="inline-block max-w-full bg-white border border-slate-200/80 rounded-2xl rounded-bl-[5px] px-3 py-1.5 shadow-sm relative">
                          <div class="flex items-center mb-1 gap-3 w-64 rounded-xl bg-neutral-800 p-2.5 text-white">

 <button data-audio="" onclick="VoiceNote(this)" id="VN${mess['message_id']}"
    class="playVN w-9 h-9 shrink-0 rounded-full bg-orange-600 flex items-center justify-center text-white">
    <i class="fa-solid fa-play text-sm"></i>
</button>

<button type="button" id="${mess['message_id']}"
    class="VoiceNote -ml-12 relative w-9 h-9 shrink-0 rounded-full bg-white/70 backdrop-blur-sm border border-white/50 shadow-sm flex items-center justify-center text-orange-600 hover:bg-white/90 transition"
    title="Download voice note">
    <i class="fa-solid fa-download text-lg"></i>
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
            <span>${mess['media']}secs</span>
        </div>
    </div>
<p class="VnSpeaker"><i class="fa-solid fa-volume-xmark text-neutral-400"></i></p>
    
            </div>                            
                                                    <!-- Timestamp -->
                                                    <div class="flex justify-end mt-1">
                                                        <span class="text-[9px] text-slate-400">
                                                            ${new Date().toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'})}
                                                        </span>
                                                    </div>

                                                </div>

                                                <!-- Reactions -->
                                                <div class="flex justify-start -mt-2 pl-2 relative z-10">
                                                    <div class="inline-flex items-center gap-1 bg-white border border-slate-200 rounded-full px-2.5 py-0.5 shadow-sm text-xs react${mess['message_id']}">
                                                        <span class="text-[10px] font-bold text-slate-500 pl-0.5 react${mess['message_id']}">0</span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        `
                                        ;

                                    MessageHolder.insertAdjacentHTML('beforeend', Message_Temp);

                                  
                                
                                    MessageHolder.scrollTop = MessageHolder.scrollHeight;
    getVoiceNote()                        
}

function videoHandler(mess){
    console.log(mess);
}
//REPLY SECTION
function replyMe(){
const replyMess=document.querySelectorAll('.replyMess');

replyMess.forEach((rep)=>{
    rep.addEventListener("click",()=>{
            const cls=".mit"+rep.getAttribute('originalId')
            const real=document.querySelector(cls);
            const realClass=real.className;
            real.className="relative ring-2 ring-orange-400/80 bg-gradient-to-r from-orange-500/20 via-orange-400/10 to-transparent shadow-[0_0_30px_rgba(249,115,22,0.25)] rounded-2xl scale-[1.02] transition-all duration-500 before:absolute before:inset-y-0 before:left-0 before:w-1 before:rounded-l-2xl before:bg-gradient-to-b before:from-orange-300 before:via-orange-500 before:to-amber-400"
            real.scrollIntoView({ behavior: "smooth", block: "start" });    

            setTimeout(()=>{
                real.className=realClass
            },1900)
    })
})
}

replyMe()

function stb() {
    const scrollToBottom=document.querySelector("#scrollToBottom");
    scrollToBottom.addEventListener("click",()=>{
  
document.querySelector("#MessageHolder").scrollTo({
    top: document.querySelector("#MessageHolder").scrollHeight,
    behavior: "smooth"
});
    })
}

stb()


///DOWNLOAD THUMBNAIL PICTURES

function thumbDownloadFunc(){
    const thumbDownload=document.querySelectorAll('.thumbDownload');

    thumbDownload.forEach(tr=>{

            tr.addEventListener('click',async function () {

                document.querySelector(".topErr").classList.add('hidden');
                document.querySelector(".pageError").innerHTML=''

                //replace download Icon to loader;
                const loader = document.createElement('p');

                    loader.className = 'w-10 h-10 border-2 border-gray-300 border-t-blue-400 rounded-full animate-spin';

                    tr.replaceChildren(loader);

                
               try {
                const message_id=tr.id;
                console.log(message_id);
                const formdata=new FormData();
                formdata.append('id',message_id)
                
                const response= await fetch("/api/image/getThumbNailedImage",{
                            method:'POST',
                            headers:{
                                   'Accept': 'application/json',
                                   'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body:formdata

                        })

                        const data=await response.json();
                        
                            if (data.success) {
                                console.log(data,'no error');
                                ///change image SRC
                                const imgId='#thu'+message_id;
                            document.querySelector(imgId).src="data:image/png;base64,"+data['src']['base64'];
                            tr.remove();
                            }else{
                                document.querySelector(".topErr").classList.remove('hidden');
                                document.querySelector(".pageError").innerHTML=data['message']
                                setTimeout(() => {
                                     document.querySelector(".topErr").classList.add('hidden');
                                document.querySelector(".pageError").innerHTML=''
                                }, 2500);
                                console.log(data,'error')
                                const i=document.createElement('i');
                                i.className="fa-solid fa-download text-white text-xl";
                                tr.replaceChildren(i);
                            }
               } catch (error) {
                console.log(error)
               }
            })
    })
}

function getVoiceNote() {

    let buttons=document.querySelectorAll(".VoiceNote")
    buttons.forEach((btn)=>{
               btn.addEventListener('click',async()=>{
        const message_id=btn.id
        console.log(message_id);
        const loader = document.createElement('p');

        loader.className = 'w-10 h-10 border-2 border-gray-300 border-t-blue-400 rounded-full animate-spin';

        btn.replaceChildren(loader);

        const formData=new FormData();
        formData.append('id',message_id);

        try {
              const response= await fetch("/api/voiceNote/getVoiceNote",{
                            method:'POST',
                            headers:{
                                   'Accept': 'application/json',
                                   'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body:formData

                        })

                        const data=await response.json();
                        
                            if (data.success) {
                                console.log(data,'no error');
                                ///change VN SRC
                                const VNId='#VN'+message_id;
                            document.querySelector(VNId).setAttribute("data-audio",data['src']['base64']);
                            btn.remove();
                            }else{
                                document.querySelector(".topErr").classList.remove('hidden');
                                document.querySelector(".pageError").innerHTML=data['message']
                                setTimeout(() => {
                                     document.querySelector(".topErr").classList.add('hidden');
                                document.querySelector(".pageError").innerHTML=''
                                }, 2500);
                                console.log(data,'error')
                                const i=document.createElement('i');
                                i.className="fa-solid fa-download text-white text-xl";
                                btn.replaceChildren(i);
                            }
        } catch (error) {
             console.log(error)
             const i=document.createElement('i');
             i.className="fa-solid fa-download text-white text-xl";
            btn.replaceChildren(i);
        }
    })
    })

 
}