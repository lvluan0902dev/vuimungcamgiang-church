<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    protected $guarded = [];

    public function admin_user() {
        return $this->belongsTo(AdminUser::class, 'admin_user_id', 'id');
    }

    public function album_images() {
        return $this->hasMany(AlbumImage::class, 'album_id', 'id');
    }
}
