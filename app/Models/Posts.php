<?php 

/**
 * Created By Lazade with artisan
 * Created At 2018-10-29
 * */ 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Posts extends Model
{
    
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title', 'flag'];

    public function scopeOfType($query, $type)
    {
        return $query;
    }

    /**
     * scope of tag inquirer
     * @param $query 
     * @param $tag_id
     * @return mixed
     * */ 
    public function scopeOfTag($query, $tag_id)
    {
        if (intval($tag_id) > 0) {
            return $query->where('tag_id', $tag_id);
        }
        return $query;
    }

    /**
     * scope of title inquirer %like
     * @param $query
     * @param $title
     * @return mixed
     * */ 
    public function scopeOfTitle($query, $title)
    {
        if (!empty($title)) {
            return $query->where('title', 'like', '%' . $title . '%');
        }
        return $query;
    }

    public function tag()
    {
        return $this->belongsTo(Tags::class, 'tag_id');
    }

    public function previous()
    {
        return $this->where([['id', '<', $this->id], ['state', '=', 1]])->orderBy('id', 'desc')->first();
    }

    public function GroupByYear($year)
    {
        return $this->where('state', 1)->whereYear('created_at', $year)->orderBy('id', 'desc')->get();
    }

}