
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
                    <input type="text" id="sidebarGroupSearch" placeholder="Search synced groups..." 
                        class="w-full pl-9 pr-3 py-2 bg-white border border-slate-200 rounded-xl text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all">
                </div>
            </div>

            <!-- Group Chat List Stream -->
            <div class="flex-1 overflow-y-auto divide-y divide-slate-100">
                @foreach ($groups as $group)
                    @php
                        $latestChat = $group->latest_chat;
                        $isActive = $group->group_id === $group_Id;
                        $chatTime = $latestChat
                            ? \Carbon\Carbon::parse($latestChat->message_timestamp)
                            : ($group->subjectTime ? \Carbon\Carbon::parse($group->subjectTime) : null);
                        $senderName = $latestChat
                            ? ($latestChat->is_from_me ? 'You' : ($latestChat->sender_name ?: 'Unknown'))
                            : 'No messages';
                    @endphp
                    <a href="{{ route('groupChat', ['id' => encrypt($group->group_id)]) }}"
                       class="group-sidebar-item block p-3.5 {{ $isActive ? 'bg-orange-50/70 border-l-4 border-orange-600' : 'hover:bg-slate-50 border-l-4 border-transparent' }} cursor-pointer transition-all"
                       data-group-name="{{ strtolower($group->subject ?: '') }}"
                       data-group-id="{{ $group->group_id }}">
                        <div class="flex justify-between items-start mb-1">
                            <h3 class="text-xs {{ $isActive ? 'font-bold' : 'font-semibold' }} text-slate-900 truncate max-w-[170px]"
                                title="{{ $group->subject }}">
                                {{ $group->subject ?: 'Unnamed group' }}
                            </h3>
                            @if ($chatTime)
                                <span class="sidebar-time {{ $isActive ? 'text-orange-600' : 'text-slate-400' }} text-[10px] font-medium whitespace-nowrap"
                                      title="{{ $chatTime->format('M j, Y g:i A') }}">
                                    @if ($chatTime->isToday())
                                        {{ $chatTime->format('g:i A') }}
                                    @elseif ($chatTime->isYesterday())
                                        Yesterday
                                    @else
                                        {{ $chatTime->format('M j') }}
                                    @endif
                                </span>
                            @endif
                        </div>
                        <p class="text-xs {{ $isActive ? 'text-slate-600' : 'text-slate-500' }} truncate">
                            @if ($latestChat)
                                <strong>{{ $senderName }}:</strong> {{ $latestChat->body ?: '[Media/message]' }}
                            @else
                                <span>No messages yet</span>
                            @endif
                        </p>
                        <div class="flex items-center justify-between mt-2">
                            <span class="text-[10px] bg-slate-100 text-slate-500 px-2 py-0.5 rounded font-mono">
                                {{ $chatTime ? $chatTime->format('M j, Y g:i A') : 'No activity' }}
                            </span>
                            <span class="text-[10px] text-slate-400">{{ $group->size ?: 0 }} members</span>
                        </div>
                    </a>
                @endforeach

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