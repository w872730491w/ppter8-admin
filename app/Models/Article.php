<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'content', 'sort', 'seo_title', 'keywords', 'description', 'cover', 'votes', 'views', 'comments', 'article_cate_id',
        'user_id', 'review_status', 'review_content', 'status', 'tag'
    ];

    /**
     * 访问器被附加到模型数组的形式。
     *
     * @var array
     */
    protected $appends = [
        'text'
    ];

    public function votes()
    {
        return $this->belongsToMany('App\Models\User', 'user_vote_materials');
    }

    public function collects()
    {
        return $this->belongsToMany('App\Models\User', 'user_collect_materials');
    }

    public function comment()
    {
        return $this->morphMany('App\Models\Comment', 'commentable');
    }

    public function cate()
    {
        return $this->belongsTo('App\Models\ArticleCate', 'article_cate_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function getTextAttribute()
    {
        $text = trim(strip_tags($this->attributes['content']));
        if ($this->attributes['cover']) {
            $text = mb_substr($text, 0, 148) . '...';
        } else {
            $text = mb_substr($text, 0, 180) . '...';
        }
        return $text;
    }

    public function setContentAttribute($value)
    {
        $this->attributes['content'] = preg_replace('#<script[^>]*?>.*?</script>#si', '', $value);
    }

    public function getCoverAttribute($cover)
    {
        return $cover ? $cover : '/static/index/image/default-cover.png';
    }
}
