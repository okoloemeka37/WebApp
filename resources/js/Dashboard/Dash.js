/// DashSearch

const DashSearch=document.querySelector("#DashSearch");

let searchTimeout;

DashSearch.addEventListener('input', () => {
    clearTimeout(searchTimeout);

    searchTimeout = setTimeout(async () => {
        const data = new FormData();
        data.append('search', DashSearch.value);

        if (DashSearch.value.length ===0) {
           document.querySelector(".realbody").classList.remove("hidden")
           document.querySelector(".fakebody").classList.add("hidden");
        }else{
          try {
            const response = await fetch("/api/Dashboard/Dashsearch", {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: data
            });

            const result = await response.json();

            console.log(result);

            if (response.ok && result.success) {
               document.querySelector(".fakebody").innerHTML=''
                document.querySelector('#compose-error-message').classList.add('hidden');

                const data=result.data;
                if (data.length !==0) {
                  document.querySelector(".realbody").classList.add("hidden")
                  document.querySelector(".fakebody").classList.remove("hidden");
                     data.forEach(dt => {
                          const temp_Dash=`
                
                     <tr class="hover:bg-orange-50/30 transition-colors">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-slate-800 text-white font-bold flex items-center justify-center text-xs shrink-0">
                                        
                                    </div> 
                                    <div>
                                        <span title="${dt['subject']}" class="font-bold text-slate-900 text-sm block truncate ">${dt['subject']}</span>
                                        <span class="text-[11px] text-slate-400">18 Members</span>
                                    </div>
                                </div>
                            </td>
                            
                            <td class="py-4 px-4">
                                <div>
                                    <span class="text-slate-900 font-semibold block">Sarah Jenkins</span>
                                    <span class="text-[11px] text-slate-400">09:15 AM</span>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <span class="text-slate-400 text-[11px]">0</span>
                            </td>
                            <td class="py-4 px-4">
                                <span class="bg-emerald-50 text-emerald-700 font-semibold px-2.5 py-1 rounded-lg border border-emerald-100">
                                    Active Sync
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <a href="/groupChat/${dt['url']}" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold transition-all">
                                    View
                                </a>
                            </td>
                        </tr>

                `
                 document.querySelector(".fakebody").insertAdjacentHTML('beforeend',temp_Dash )
                     });
                }else{
                    document.querySelector(".realbody").classList.add("hidden")
                  document.querySelector(".fakebody").classList.remove("hidden");
                  const p=`<p class="class="flex flex-col items-center justify-center py-16 px-6 text-center rounded-2xl border border-dashed border-slate-700/70 bg-slate-800/30"">Nothing Was Found</p>`
                  document.querySelector(".fakebody").insertAdjacentHTML('beforeend',p )
               }
             
            } else {
                const errorElement = document.querySelector('#compose-error-message');
                errorElement.classList.remove('hidden');
                errorElement.textContent = result.error?.message || 'Search failed';
            }

        } catch (error) {
            console.error(error);

            const errorElement = document.querySelector('#compose-error-message');
            errorElement.classList.remove('hidden');
            errorElement.textContent = 'Something went wrong';
        }
        }
       
    }, 500);
});