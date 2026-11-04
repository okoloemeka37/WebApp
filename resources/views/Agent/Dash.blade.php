@extends('agent.layout')
@section('title','Dashboard')
<meta name="csrf-token" content="{{ csrf_token() }}"></meta>

@vite(['resources/js/Dashboard/Dash.js'])
@section('content')

<div class="h-full flex flex-col bg-slate-50 text-slate-800">

   

    <!-- MAIN DASHBOARD CONTENT (GROUPS & METRICS OVERVIEW) -->
    <main class="flex-1 overflow-y-auto p-6 max-w-7xl w-full mx-auto space-y-6">


        <!-- 1. STATS OVERVIEW CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Synced Groups</span>
                    <div class="w-8 h-8 rounded-lg bg-orange-50 text-orange-500 flex items-center justify-center">
                        <i class="fa-solid fa-users text-sm"></i>
                    </div>
                </div>
                <div class="text-2xl font-bold text-slate-900">{{ $countGroup }}</div>
                <p class="text-xs text-slate-500 mt-1"><span class="text-orange-600 font-semibold">100%</span> active on phone</p>
            </div>

            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pending Messages</span>
                    <div class="w-8 h-8 rounded-lg bg-orange-50 text-orange-500 flex items-center justify-center">
                        <i class="fa-solid fa-envelope-open-text text-sm"></i>
                    </div>
                </div>
                <div class="text-2xl font-bold text-slate-900">14</div>
                <p class="text-xs text-orange-600 font-medium mt-1">Requires agent response</p>
            </div>

            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Today's Traffic</span>
                    <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center">
                        <i class="fa-solid fa-chart-line text-sm"></i>
                    </div>
                </div>
                <div class="text-2xl font-bold text-slate-900">1,284</div>
                <p class="text-xs text-slate-500 mt-1">Inbound & outbound messages</p>
            </div>

            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Avg. Response Time</span>
                    <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center">
                        <i class="fa-solid fa-clock text-sm"></i>
                    </div>
                </div>
                <div class="text-2xl font-bold text-slate-900">3.4m</div>
                <p class="text-xs text-emerald-600 font-medium mt-1">⚡ Dynamic delay active</p>
            </div>

        </div>
          
        <div id="compose-error-message" class="hidden rounded-lg border border-red-500/20 bg-red-500/10 px-4 py-3 text-sm text-red-400"></div>

        <!-- 2. GROUPS MANAGEMENT TABLE CONTAINER -->
        <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden">
            
            <!-- Table Controls & Search -->
            <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">WhatsApp Group </h2>
                    <p class="text-xs text-slate-500">Monitor activity across all connected groups</p>
                </div>

                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <!-- Search Input -->
                    <div class="relative flex-1 sm:w-64">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        </span>
                        <input type="text" id="DashSearch" placeholder="Search  groups..." class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all">
                    </div>

                    <!-- Filter Dropdown -->
                    <button class="px-3.5 py-2 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 transition-colors flex items-center gap-2 shrink-0">
                        <i class="fa-solid fa-filter text-slate-400"></i>
                        <span>Filter</span>
                    </button>
                </div>
            </div>

            <!-- Groups Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider">
                        <tr>
                            <th class="py-3.5 px-6">Group Name</th>
                         
                            <th class="py-3.5 px-4">Latest Activity</th>
                            <th class="py-3.5 px-4">Unread</th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700 realbody">
                        
                      {{--   <!-- Row 1 -->
                        <tr class="hover:bg-orange-50/30 transition-colors">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-orange-500 text-white font-bold flex items-center justify-center text-xs shrink-0 shadow-sm shadow-orange-500/20">
                                        VIP
                                    </div>
                                    <div>
                                        <span class="font-bold text-slate-900 text-sm block">VIP Client Cohort A</span>
                                        <span class="text-[11px] text-slate-400">42 Members</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4 font-mono text-slate-500 text-[11px]">
                                12036301234567@g.us
                            </td>
                            <td class="py-4 px-4">
                                <div>
                                    <span class="text-slate-900 font-semibold block">Michael Vance</span>
                                    <span class="text-[11px] text-slate-400">10:42 AM</span>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <span class="bg-orange-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">
                                    3 new
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                <span class="bg-emerald-50 text-emerald-700 font-semibold px-2.5 py-1 rounded-lg border border-emerald-100">
                                    Active Sync
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <button class="px-3 py-1.5 bg-orange-500 hover:bg-orange-600 text-white rounded-lg text-xs font-semibold shadow-sm shadow-orange-500/20 transition-all">
                                    Open Group
                                </button>
                            </td>
                        </tr> --}}

                        @foreach ($groups as $group)
                              <!-- Row 2 -->
                        <tr class="hover:bg-orange-50/30 transition-colors">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                  {{--   <div class="w-9 h-9 rounded-xl bg-slate-800 text-white font-bold flex items-center justify-center text-xs shrink-0">
                                        {{ $group['subject'] }}
                                    </div> --}}
                                    <div>
                                        <span title="{{ $group['subject'] }}" class="font-bold text-slate-900 text-sm block truncate ">{{ $group['subject'] }}</span>
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
                                <a href="{{ route('groupChat',['id'=>encrypt($group['group_id']) ]) }}" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold transition-all">
                                    View
                                </a>
                            </td>
                        </tr>
                        @endforeach


                    </tbody>

                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700 hidden fakebody"></tbody>
                </table>
            </div>

            <!-- Table Pagination Footer -->
          {{--   <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Showing 1 to 3 of 162 WhatsApp Groups</span>
                <div class="flex items-center gap-1">
                    <button class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg hover:bg-slate-100 font-medium">Previous</button>
                    <button class="px-3 py-1.5 bg-orange-500 text-white rounded-lg font-bold">1</button>
                    <button class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg hover:bg-slate-100 font-medium">2</button>
                    <button class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg hover:bg-slate-100 font-medium">...</button>
                    <button class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg hover:bg-slate-100 font-medium">54</button>
                    <button class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg hover:bg-slate-100 font-medium">Next</button>
                </div>
            </div> --}}

           
                    <div class="container  m-10 ">{{ $groups->links() }}</div>
            
        </div>

    </main>

</div>
@endsection