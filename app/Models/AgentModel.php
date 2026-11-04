<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AgentModel extends Authenticatable
{
    protected $table='agents';
    protected $fillable=['password','name','email','role'];
}
