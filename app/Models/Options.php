<?php 

/**
 * Created By Lazade with artisan
 * Created At 2018-10-29
 * */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Options extends Model
{

    protected $fillable = ['option_key', 'option_value'];

    public function scopeNotbase($query)
    {
        return $query->where('type', '!=', 'base');
    }

    // public function scopeNothidden($query)
    // {
    //     return $query->where('option_status', '!=', 'hidden');
    // }

}