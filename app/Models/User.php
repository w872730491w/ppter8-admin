<?php

namespace App\Models;

use App\Models\Vip as VipsModel;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'password', 'nickname', 'sex', 'birthday', 'qq', 'mobile', 'wx_openid',
        'qq_openid', 'unionid', 'avatar', 'province', 'city', 'district', 'last_login', 'last_ip', 'total_amount',
        'status', 'desc', 'uid', 'can_cash_money', 'shares', 'user_realname', 'user_vest_type', 'user_reg_type', 'user_identity', 'user_reg_ip', 'user_reg_type', 'user_reg_address',
        'user_mobile_province', '	user_reg_city', 'user_reg_address', '	user_reg_location', 'user_mobile_carrier', 'user_mobile_location',
        'shares_reg', 'count_download'
    ];

    protected $appends = ['notify_counts', 'chat_counts'];

    // 注册
    // 素材审核或失败
    // 作者的审核或失败
    // 提现的成功或失败 (积分或余额)
    // 素材卖出去
    // xxx 评论了您的 (素材或文章)

    public function shares()
    {
        return $this->hasMany('App\Models\Share');
    }

    public function vip()
    {
        return $this->hasMany(UserVip::class, 'user_id', 'id');
    }

    public function user_download()
    {
        return $this->hasMany(UserDownload::class, 'user_id', 'id');
    }

    public function UserMaterials()
    {
        return $this->belongsToMany('App\Models\Material', 'user_materials')->withTimestamps();
    }

    public function materials()
    {
        return $this->hasMany('App\Models\Material', 'author', 'id');
    }

    public function user_message()
    {
        return $this->hasMany('App\Models\UserMessage');
    }

    public function entry()
    {
        return $this->hasMany('App\Models\UserEntry');
    }

    public function isAuthor()
    {
        return $this->entry()->where('review_status', 1)->count() > 0 ? true : false;
    }

    public function user_vote_articles()
    {
        return $this->belongsToMany('App\Models\Article', 'user_vote_articles')->withTimestamps();
    }

    public function user_collect_articles()
    {
        return $this->belongsToMany('App\Models\Article', 'user_collect_articles')->withTimestamps();
    }

    public function user_vote_comments()
    {
        return $this->belongsToMany('App\Models\Comment', 'user_vote_comments')->withTimestamps();
    }

    public function user_vote_materials()
    {
        return $this->belongsToMany('App\Models\Material', 'user_vote_materials')->withTimestamps();
    }

    public function articles()
    {
        return $this->hasMany('App\Models\Article');
    }

    /**
     * Get the entity's notifications.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function notifications()
    {
        return $this->morphMany(DatabaseNotification::class, 'notifiable')->orderBy('read_at', 'asc')->orderBy('created_at', 'desc');
    }

    public function chat_lists()
    {
        return $this->hasMany('App\Models\ChatList');
    }

    public function incomes()
    {
        return $this->hasMany('App\Models\UserIncome');
    }

    public function getVip()
    {
        $vip = $this->vip()->where('vip_id', '!=', 11)->where(function ($query) {
            $query->where('due_time', '>', time())
                ->orWhere('due_time', 0);
        })->orderBy('created_at', 'desc')->first();
        if (!$vip) {
            $vip = false;
        }
        return $vip;
    }

    /*
     * 获取用户vip购买列表
     * */
    public function isVip(int $vipCate)
    {
        $vips = VipsModel::where('vip_cate_id', $vipCate)->select('id')->get()->toarray();
        $vip = $this->vip()->where(function ($query) {
            $query->where('due_time', '>', time())
                ->orWhere('due_time', 0);
        })->whereIn('vip_id', $vips)->count();
        if (!$vip) {
            $vip = false;
        }
        return $vip;
    }

    public function order()
    {
        return $this->hasMany('App\Models\Order');
    }

    public function cash_info()
    {
        return $this->hasOne('App\Models\UserCashInfo');
    }

    public function user_point()
    {
        return $this->hasMany('App\Models\UserPoint');
    }

    public function cashes()
    {
        return $this->hasMany('App\Models\Cash');
    }

    public function customizeds()
    {
        return $this->hasMany('App\Models\Customized');
    }

    public function getNotifyCountsAttribute()
    {
        $count = $this->unreadNotifications()->count();
        return $count > 99 ? '99+' : $count;
    }

    public function getChatCountsAttribute()
    {
        $list = ChatList::where('user_id', auth()->id())
            ->orWhere('another_user_id', auth()->id())
            ->withCount([
                'chats' => function ($query) {
                    $query->where('read_at', null);
                }
            ])
            ->get();
        return $list->sum('chats_count');
    }
}
