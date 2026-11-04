const MessageHolder = document.querySelector("#MessageHolder");
MessageHolder.scrollTop = MessageHolder.scrollHeight;

const message_form=document.querySelector("#message_form");

message_form.addEventListener('submit',async(e)=>{
    e.preventDefault()
    const formdata=new FormData(message_form);
     document.querySelector('#compose-error-message').classList.add('hidden')
     document.querySelector('#compose-error-message').innerHTML='';
    document.querySelector("#MessSpin").classList.remove('hidden')
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

                           
                      const MessageHolder = document.querySelector("#MessageHolder");
const input = document.querySelector("#input_Mess");
const inputMessValue = input.value.trim();

if (inputMessValue !== "") {
    const safeMessage = document.createElement("p");
    safeMessage.className = "text-xs leading-relaxed";
    safeMessage.textContent = inputMessValue;

    const Message_Temp = `
        <div class="flex items-end justify-end gap-2">
            <div class="bg-orange-600 text-white rounded-2xl rounded-br-sm p-3.5 shadow-md shadow-orange-600/10 max-w-xl">
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
                        }
                    } catch (error) {
                        
                        console.log(error)
                        document.querySelector('#compose-error-message').innerHTML='Something went wrong';
document.querySelector("#MessSpin").classList.add('hidden')
                         
                    } 
})







///Handle repling message
//getting all chat to enable double click;
const chatM=document.querySelectorAll('.chatM');

chatM.forEach(ind=>{
    ind.addEventListener('dblclick',()=>{
       document.querySelector('#ReplyPreview').classList.remove('hidden')
    })
})

const CancelReply=document.querySelector('#CancelReply');
CancelReply.addEventListener('click',()=>{
document.querySelector('#ReplyPreview').classList.add('hidden')
})