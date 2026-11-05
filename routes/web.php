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