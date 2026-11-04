<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    protected $table='participants';
    protected $fillable=['isAdmin','phoneNumber','participant_id','group_id'];
}
