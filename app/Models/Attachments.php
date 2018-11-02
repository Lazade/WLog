<?php

/**
 * Created By Lazade with artisan
 * Created At 2018-10-29
 * */ 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachments extends Model
{
    
    protected $fillable = ['path', 'hash1', 'md5'];

}
