let ChatId=[];
function srt(){
    const allChats=document.querySelectorAll('.chatM');

allChats.forEach(ct => {
    let snip={id:ct['id'],name:ct.getAttribute('name'),'body':ct.getAttribute('body')}
    ChatId.push(snip);
});
}

srt()

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
                    
                      const MessageHolder = document.querySelector("#MessageHolder");
                     
                    if(event.message.length > 0){
                        const messa=JSON.parse(event.message)
                        const GID=document.querySelector('#GID').value;
                     
                        if (GID==messa[0]['group_id']) {
                            
                            

                              messa.forEach(mess => {
                                let snip={id:mess['message_id'],name:mess['send_name'],'body':mess['body']}
                                ChatId.push(snip);
                                 const safeMessage = document.createElement("p");
                                    safeMessage.className = "text-xs leading-relaxed";
                                    safeMessage.textContent = mess['body'];
                                    
                                   

                                    const Message_Temp =mess['is_from_me']==1? `
                                        <div class="flex items-end justify-end gap-2 mit${mess['message_id']} chatM">
                                            <div class="bg-orange-600 text-white rounded-2xl rounded-br-sm p-3.5 shadow-md shadow-orange-600/10 max-w-xl">
                                               
                                             <!-- Original message being replied to -->
                                                
                                            <div class="reply-content"></div>

                                            <div class="flex items-center justify-between gap-4 mb-1">
                                                    <span class="text-xs font-semibold text-orange-100">
                                                        You (Zyler)
                                                    </span>
                                                    <span class="text-[10px] text-orange-200"> ${new Date().toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'})}</span>
                                                </div>

                                                <div class="message-content"></div>

                                                <div class="flex items-center justify-end gap-1 mt-1 text-[10px] text-orange-200">
                                                </div>
                                            </div>
                                        </div>
                                    `:` <div class="flex items-end gap-2 max-w-xl mit${mess['message_id']} chatM">
                    <div class="w-7 h-7 rounded-full bg-slate-300 flex items-center justify-center text-[10px] font-bold text-slate-700 shrink-0">JD</div>
                    <div class="bg-white border border-slate-200/80 rounded-2xl rounded-bl-sm p-3.5 shadow-xs">
                                
                    <div class="reply-content"></div>
                    
                    <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs font-bold text-orange-600">${mess['sender_name']}</span>
                           
                        </div>
                        <div class="message-content"></div>
                    
                        <span class="block text-[10px] text-slate-400 text-right mt-1"> ${new Date().toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'})}</span>

                    </div>
                                        </div>`;

                                    MessageHolder.insertAdjacentHTML('beforeend', Message_Temp);

                                    MessageHolder.lastElementChild
                                        .querySelector('.message-content')
                                        .replaceWith(safeMessage);
                                        
                                       
                                         const safereply = document.createElement("div");
                                         safereply.className = "text-xs leading-relaxed";
                                            safereply.innerHTML = HandleReply(mess['quoted_message_id']);
                                        MessageHolder.lastElementChild.querySelector('.reply-content').replaceWith(safereply);
                                
                                    MessageHolder.scrollTop = MessageHolder.scrollHeight;

                            }); 
                        }
                        
                    }


                    replyMe()
                    
                    //document.getElementById('connection-status').textContent ='Live update received!';
                });
        });
    

    


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