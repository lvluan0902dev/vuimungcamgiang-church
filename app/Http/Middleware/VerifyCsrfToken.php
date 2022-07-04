<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/admin/banner/update-status',
        '/admin/position/update-status',
        '/admin/google-maps/update-status',
        '/admin/phone-number/update-status',
        '/admin/email/update-status',
        '/admin/address/update-status',
        '/admin/social-network/update-status',
        '/admin/intro/update-status',
        '/admin/member/update-status',
        '/admin/song-power-point/update-status',
        '/admin/weekly-power-point/update-status',
        '/admin/news/update-status',
        '/admin/announcements/update-status',
        '/admin/original-bible-verse/update-status',
        '/admin/album/update-status',
        '/admin/album/album-image-update-status',
        '/power-point-hang-tuan/update-number-of-downloads',
        '/power-point-bai-hat/update-number-of-downloads',
        '/admin/admin-user/update-status',
    ];
}
