<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $table='groups';
    protected $fillable=['linkedParent','isCommunityAnnounce','isCommunity','announce','restrict','descId','desc','owner','group_creation_time','size','pictureUrl','subjectTime','subjectOwner','subject','group_id'];
}
