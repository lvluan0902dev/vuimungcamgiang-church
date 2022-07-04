<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OriginalBibleVerse extends Model
{
    protected $guarded = [];

    public function admin_user() {
        return $this->belongsTo(AdminUser::class, 'admin_user_id', 'id');
    }
}
