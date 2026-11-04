<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title','Zyler')</title>

    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        /* Custom scrollbar for clean UI */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

     <!-- TOP NAVIGATION BAR -->
    <header class="h-16 bg-white border-b border-slate-200 px-6 flex items-center justify-between shrink-0 shadow-sm z-10">
        <!-- Brand & Gateway Identity -->
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-orange-500 text-white flex items-center justify-center shadow-md shadow-orange-500/20 shrink-0">
                <i class="fa-brands fa-whatsapp text-2xl"></i>
            </div>
            <div>
                <h1 class="font-bold text-slate-900 text-base leading-tight">Agent Dashboard</h1>
                <p class="text-xs text-slate-500 flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-orange-500 animate-pulse"></span>
                    Instance: <span class="font-mono font-medium text-slate-700">business-phone</span>
                </p>
            </div>
        </div>

       {{--  <!-- Global Operational Stats -->
        <div class="hidden md:flex items-center gap-6 text-xs font-semibold">
            <div class="flex items-center gap-2 bg-orange-50 text-orange-700 border border-orange-200/80 px-3.5 py-1.5 rounded-xl">
                <i class="fa-solid fa-layer-group text-orange-500"></i>
                <span>160+ Groups Synced</span>
            </div>
            <div class="flex items-center gap-2 bg-slate-100 text-slate-700 px-3.5 py-1.5 rounded-xl">
                <i class="fa-solid fa-server text-slate-400"></i>
                <span>Gateway: <strong class="text-slate-900">Online</strong></span>
            </div>
        </div> --}}

        <!-- Agent Profile & Presence -->
        <div class="flex items-center gap-3">
            <div class="text-right hidden sm:block">
                <p class="text-xs font-bold text-slate-900">{{ auth('agent')->user()['name'] }}</p>
                <p class="text-[11px] text-orange-600 font-medium">🟠 On Shift</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-orange-100 text-orange-600 border border-orange-200 flex items-center justify-center font-bold text-sm">{{ auth('agent')->user()['name'][0] }}</div>
        </div>
    </header>

    @yield('content')
</body>
</html>