<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Station Login | WhatsApp Group Inbox</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="h-full flex items-center justify-center p-4 sm:p-6 lg:p-8 bg-slate-50">

    <div class="w-full max-w-md space-y-6">
        
      {{--  <!-- Header & Branding -->
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-emerald-500 text-white shadow-lg shadow-emerald-500/20 mb-4">
                <i class="fa-brands fa-whatsapp text-3xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Agent Workstation</h1>
            <p class="text-sm text-slate-500 mt-1">Sign in to handle multi-group customer inquiries</p>
        </div> --}}
 
       {{--  <!-- Realtime System Status Badge -->
        <div class="flex items-center justify-between bg-white border border-slate-200 px-4 py-2.5 rounded-xl text-xs font-medium text-slate-600 shadow-sm">
            <div class="flex items-center gap-2">
                <span class="relative flex h-2.5 w-2.5">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                </span>
                <span>Gateway Status: <strong class="text-slate-800">Connected</strong></span>
            </div>
            <span class="bg-emerald-50 text-emerald-700 font-semibold px-2.5 py-0.5 rounded-md border border-emerald-100/80">
                160+ Groups Synced
            </span>
        </div>
 --}}
        <!-- Login Card Container -->
        <div class="bg-white border border-slate-200/80 shadow-xl shadow-slate-200/50 rounded-2xl p-6 sm:p-8">

            <form class="space-y-4" method="POST" action="{{ route('PostAgentLogin') }}">
            @if($errors->has('failed'))<div class="bg-red-100 text-red-700 p-4 rounded-lg">{{$errors->first('failed')}}</div> @endif

                    @csrf
              {{--   <!-- Access Role Selector -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Access Role</label>
                    <div class="grid grid-cols-2 gap-2 p-1 bg-slate-100/80 rounded-xl border border-slate-200/60">
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="agent" class="peer hidden" checked>
                            <span class="flex items-center justify-center gap-2 py-2 px-3 text-xs font-semibold rounded-lg text-slate-600 peer-checked:bg-white peer-checked:text-emerald-600 peer-checked:shadow-sm transition-all">
                                <i class="fa-solid fa-headset text-sm"></i>
                                Agent
                            </span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="supervisor" class="peer hidden">
                            <span class="flex items-center justify-center gap-2 py-2 px-3 text-xs font-semibold rounded-lg text-slate-600 peer-checked:bg-white peer-checked:text-emerald-600 peer-checked:shadow-sm transition-all">
                                <i class="fa-solid fa-user-shield text-sm"></i>
                                Supervisor
                            </span>
                        </label>
                    </div>
                </div> --}}

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-regular fa-envelope"></i>
                        </div>
                        <input id="email" type="email" placeholder="agent@company.com" name="email"
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-slate-900 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
                    
                        </div>
                        <p class="text-red-500">@error('email') {{ $message }}@enderror</p>
                </div>

                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">Password</label>
                        <a href="#" class="text-xs text-emerald-600 hover:text-emerald-700 font-medium">Forgot password?</a>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <input id="password" type="password" placeholder="••••••••" name="password"
                            class="w-full pl-10 pr-10 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-slate-900 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
                    </div>
                     <p class="text-red-500">@error('password') {{ $message }}@enderror</p>
                </div>

               {{--  <!-- Initial Presence Status -->
                <div>
                    <label for="initial_status" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Shift Status on Login</label>
                    <select id="initial_status" class="w-full px-3.5 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
                        <option value="online">🟢 Online — Ready for incoming messages</option>
                        <option value="busy">🟡 Busy — Internal administrative work</option>
                        <option value="break">☕ On Break</option>
                    </select>
                </div> --}}

                <!-- Remember Me -->
                <div class="flex items-center pt-1">
                    <input id="remember_me" name="remember" type="checkbox" class="w-4 h-4 text-emerald-600 border-slate-300 rounded focus:ring-emerald-500">
                    <label for="remember_me" class="ml-2 block text-xs font-medium text-slate-600">Keep me signed in</label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl shadow-lg shadow-emerald-600/20 hover:shadow-emerald-600/30 active:scale-[0.99] transition-all duration-150 flex items-center justify-center gap-2 text-sm mt-2">
                    <span>Enter Inbox Station</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>
            </form>
        </div>

      
    </div>

</body>
</html>