<?php


use App\Http\Controllers\AgentController;
use App\Http\Controllers\GroupController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/AgentLogin',function(){
return view('Agent.AgentLogin');
})->name('GetAgentLogin');

Route::post('/AgentLogin',[AgentController::class,'login'])->name('PostAgentLogin');

Route::middleware(['agent.auth'])->group(function () {
    
    Route::get('/AgentDashboard',[AgentController::class, 'AgentDash'])->name('AgentDash');

    Route::get("/groupChat/{id}",[GroupController::class,'groupChat'])->name('groupChat');

    Route::post("/AgentLogout",[AgentController::class, 'AgentLogout'])->name('AgentLogout');
});

Route::get('/test',[GroupController::class, 'GetContacts']);

Route::get('/broadcast-test', function () {
    

    return 'Broadcast event dispatched.';
});


Route::get('/health-check', function () {
    return response()->json([
        'status' => 'Laravel is responding',
        'time' => now()->toDateTimeString()
    ]);
});

Route::get('/raw-check', function () {
    return 'Laravel is alive';
})->withoutMiddleware([
    \Illuminate\Session\Middleware\StartSession::class,
    \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
]);