<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class agentTomessage extends Model
{
    protected $table='agentTomessage';
    protected $fillable=['group_id','replyInterval','agent_id','message_id'];
}
