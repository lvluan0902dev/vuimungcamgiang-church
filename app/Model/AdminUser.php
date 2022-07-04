<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class AdminUser extends Authenticatable
{
    use Notifiable;

    protected $guarded = [];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function news() {
        return $this->hasMany(NewsAndAnnouncements::class, 'admin_user_id', 'id')->where('type' , 'news');
    }

    public function announcements() {
        return $this->hasMany(NewsAndAnnouncements::class, 'admin_user_id', 'id')->where('type' , 'announcements');
    }

    public function original_bible_verses() {
        return $this->hasMany(OriginalBibleVerse::class, 'admin_user_id', 'id');
    }
}
