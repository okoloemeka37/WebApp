<?php

namespace App\Models\chat;

use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    protected $table='reaction';
    protected $fillable=['is_from_me','participant_contact_id','group_id','reaction_id','body','reactionTo','status','message_timestamp','message_type'];
}
