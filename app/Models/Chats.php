<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chats extends Model
{
   protected $table='chats';
   protected  $fillable=['status','source','participant_contact_id','message_timestamp','quoted_message_id','is_from_me','body','message_type','sender_name','group_id','message_id'];
}
