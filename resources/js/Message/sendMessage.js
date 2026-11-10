const MessageHolder = document.querySelector("#MessageHolder");
MessageHolder.scrollTop = MessageHolder.scrollHeight;

const message_form=document.querySelector("#message_form");


let message_id='';
let participantId='';
let replyName='';
let replyBody='';


message_form.addEventListener('submit',async(e)=>{
    e.preventDefault()
    const formdata=new FormData(message_form);
        if (message_id.length !==0) {
            formdata.append('isReply',true)
        }else{formdata.append('isReply',false)}

            formdata.append('messageId',message_id)
            formdata.append('participantId',participantId)

     document.querySelector('#compose-error-message').classList.add('hidden')
     document.querySelector('#compose-error-message').innerHTML='';
    document.querySelector("#MessSpin").classList.remove('hidden')

    console.log(message_id);
     try {
                        const response= await fetch("/api/message/sendMessage",{
                            method:'POST',
                            headers:{
                                   'Accept': 'application/json',
                                   'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body:formdata

                        })

                        const data=await response.json();
                        document.querySelector("#MessSpin").classList.add('hidden')
                            if (!data.success) {
                                document.querySelector('#compose-error-message').classList.remove('hidden')
                               document.querySelector('#compose-error-message').innerHTML=data.error.message;
                                
                            }

                        if (!response.ok) {
                            throw new Error(
                               // data.message || 'Something went wrong.'
                            );
                        } 
                        
                        if (data.success) {

                           const ret=data.data;

                      const MessageHolder = document.querySelector("#MessageHolder");
const input = document.querySelector("#input_Mess");
const inputMessValue = input.value.trim();

if (inputMessValue !== "") {
    const safeMessage = document.createElement("p");
    safeMessage.className = "text-xs leading-relaxed";
    safeMessage.textContent = inputMessValue;

    const Message_Temp = `
        <div class="flex items-end justify-end gap-2 mit${ret['key']['id']} chatM" id="${ret['key']['id']}" name="Zyler" body="${inputMessValue}" participantId="2349017251972@s.whatsapp.net'" >
            <div class="bg-orange-600 text-white rounded-2xl rounded-br-sm p-3.5 shadow-md shadow-orange-600/10 max-w-xl relative">
            
              <div class="bg-orange-700/70 border-l-4 cursor-pointer border-orange-300 rounded-lg px-3 py-2 mb-2 replyMess" originalId="${message_id}">
            <div class="text-xs font-semibold text-orange-200 mb-1">
                ${replyName}
            </div>

            <div class="text-sm text-orange-50/90 break-words">
                ${replyBody}
            </div>
        </div>
            
            <div class="flex items-center justify-between gap-4 mb-1">
                    <span class="text-xs font-semibold text-orange-100">
                        You (Zyler)
                    </span>
                    <span class="text-[10px] text-orange-200">
                        ${new Date().toLocaleTimeString([], {
                            hour: '2-digit',
                            minute: '2-digit'
                        })}
                    </span>
                </div>

                <div class="message-content"></div>
                        
                                                        <div class="flex justify-start -mt-2 pl-2 relative z-10">
                                                        <div class="inline-flex items-center gap-1 bg-white border border-slate-200 rounded-full px-2 py-0.5 shadow-xs text-xs react${ret['key']['id']}">
                                                                
                                                            <span class="text-[10px] font-bold text-slate-500 pl-0.5  react${ret['key']['id']}">0</span>
                                                        </div>
                                                        </div>
                <div class="flex items-center justify-end gap-1 mt-1 text-[10px] text-orange-200">
                </div>
            </div>
        </div>
    `;

    MessageHolder.insertAdjacentHTML('beforeend', Message_Temp);

    MessageHolder.lastElementChild
        .querySelector('.message-content')
        .replaceWith(safeMessage);

    input.value = '';

    MessageHolder.scrollTop = MessageHolder.scrollHeight;
}
                    
                
document.querySelector('#ReplyPreview').classList.add('hidden')
message_id='';
participantId='';
                }

                    } catch (error) {
                        
                        console.log(error)
                        document.querySelector('#compose-error-message').innerHTML='Something went wrong';
document.querySelector("#MessSpin").classList.add('hidden')
                         
                    }   

                    rep(); trg() 
})




//REPLY SECTION
function rep(){
const replyMess=document.querySelectorAll('.replyMess');
console.log("Thund")
replyMess.forEach((rep)=>{
    
    rep.addEventListener("click",()=>{
        console.log(rep)
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

rep()





///Handle repling message
//getting all chat to enable double click;
function trg() {
    const chatM=document.querySelectorAll('.chatM');

chatM.forEach(ind=>{
    ind.addEventListener('dblclick',()=>{

        replyName=ind.getAttribute('name');
        replyBody=ind.getAttribute("body");
        const PartId=ind.getAttribute("participantId");
        

        const messageId=ind.id;
        message_id=messageId;
        participantId =PartId
        document.querySelector(".reply-name").innerHTML=replyName;
        document.querySelector(".reply-body").innerHTML=replyBody.substring(0,70);
        document.querySelector('#ReplyPreview').classList.remove('hidden');
    })
})
}

trg() 

const CancelReply=document.querySelector('#CancelReply');
CancelReply.addEventListener('click',()=>{
document.querySelector('#ReplyPreview').classList.add('hidden')
message_id='';
participantId='';
})