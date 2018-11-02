<?php 

/**
 * Created By Lazade with artisan
 * Created At 2018-10-29
 * */

 namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tags extends Model
{
    protected $fillable = ['tag_name', 'tag_flag'];

    public function posts() 
    {
        return $this->hasMany(Posts::class, 'tag_id')->where('state', 1)->orderBy('id', 'desc');
    }
}