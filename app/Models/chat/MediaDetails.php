<?php

namespace App\Models\chat;

use Illuminate\Database\Eloquent\Model;

class MediaDetails extends Model
{
    protected $table='mediaDetails';
    protected $fillable=['waveform','seconds','thumbnailBase64','base64','width','height','fileLength','mimetype','group_id','message_id','type'];

    protected $casts = [
    'waveform' => 'array',
];

    }
