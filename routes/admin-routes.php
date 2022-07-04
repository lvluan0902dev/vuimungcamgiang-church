<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['admin']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});

Route::prefix('admin')->namespace('Admin')->group(function () {
    // Auth
    Route::get('login', 'AuthController@login')->name('admin.auth.login');
    Route::post('login-post', 'AuthController@loginPost')->name('admin.auth.login-post');

    Route::group(['middleware' => ['admin']], function () {
        // Auth
        Route::get('logout', 'AuthController@logout')->name('admin.auth.logout');

        // Dashboard
        Route::prefix('dashboard')->group(function () {
            Route::get('/', 'DashboardController@index')->name('admin.dashboard.index');
        });

        // Banner
        Route::prefix('banner')->group(function () {
            Route::get('/', 'BannerController@index')->name('admin.banner.index');
            Route::post('tim-kiem', 'BannerController@search')->name('admin.banner.search');
            Route::get('create', 'BannerController@create')->name('admin.banner.create');
            Route::post('store', 'BannerController@store')->name('admin.banner.store');
            Route::get('edit/{id}', 'BannerController@edit')->name('admin.banner.edit');
            Route::post('update/{id}', 'BannerController@update')->name('admin.banner.update');
            Route::post('update-status', 'BannerController@updateStatus');
            Route::get('delete/{id}', 'BannerController@delete');
        });

        // Position
        Route::prefix('position')->group(function () {
            Route::get('/', 'PositionController@index')->name('admin.position.index');
            Route::post('tim-kiem', 'PositionController@search')->name('admin.position.search');
            Route::get('create', 'PositionController@create')->name('admin.position.create');
            Route::post('store', 'PositionController@store')->name('admin.position.store');
            Route::get('edit/{id}', 'PositionController@edit')->name('admin.position.edit');
            Route::post('update/{id}', 'PositionController@update')->name('admin.position.update');
            Route::post('update-status', 'PositionController@updateStatus');
            Route::get('delete/{id}', 'PositionController@delete');
        });

        // GoogleMaps
        Route::prefix('google-maps')->group(function () {
            Route::get('/', 'GoogleMapsController@index')->name('admin.google-maps.index');
            Route::post('tim-kiem', 'GoogleMapsController@search')->name('admin.google-maps.search');
            Route::get('create', 'GoogleMapsController@create')->name('admin.google-maps.create');
            Route::post('store', 'GoogleMapsController@store')->name('admin.google-maps.store');
            Route::get('edit/{id}', 'GoogleMapsController@edit')->name('admin.google-maps.edit');
            Route::post('update/{id}', 'GoogleMapsController@update')->name('admin.google-maps.update');
            Route::post('update-status', 'GoogleMapsController@updateStatus');
            Route::get('delete/{id}', 'GoogleMapsController@delete');
        });

        // PhoneNumber
        Route::prefix('phone-number')->group(function () {
            Route::get('/', 'PhoneNumberController@index')->name('admin.phone-number.index');
            Route::post('tim-kiem', 'PhoneNumberController@search')->name('admin.phone-number.search');
            Route::get('create', 'PhoneNumberController@create')->name('admin.phone-number.create');
            Route::post('store', 'PhoneNumberController@store')->name('admin.phone-number.store');
            Route::get('edit/{id}', 'PhoneNumberController@edit')->name('admin.phone-number.edit');
            Route::post('update/{id}', 'PhoneNumberController@update')->name('admin.phone-number.update');
            Route::post('update-status', 'PhoneNumberController@updateStatus');
            Route::get('delete/{id}', 'PhoneNumberController@delete');
        });

        // Email
        Route::prefix('email')->group(function () {
            Route::get('/', 'EmailController@index')->name('admin.email.index');
            Route::post('tim-kiem', 'EmailController@search')->name('admin.email.search');
            Route::get('create', 'EmailController@create')->name('admin.email.create');
            Route::post('store', 'EmailController@store')->name('admin.email.store');
            Route::get('edit/{id}', 'EmailController@edit')->name('admin.email.edit');
            Route::post('update/{id}', 'EmailController@update')->name('admin.email.update');
            Route::post('update-status', 'EmailController@updateStatus');
            Route::get('delete/{id}', 'EmailController@delete');
        });

        // Address
        Route::prefix('address')->group(function () {
            Route::get('/', 'AddressController@index')->name('admin.address.index');
            Route::post('tim-kiem', 'AddressController@search')->name('admin.address.search');
            Route::get('create', 'AddressController@create')->name('admin.address.create');
            Route::post('store', 'AddressController@store')->name('admin.address.store');
            Route::get('edit/{id}', 'AddressController@edit')->name('admin.address.edit');
            Route::post('update/{id}', 'AddressController@update')->name('admin.address.update');
            Route::post('update-status', 'AddressController@updateStatus');
            Route::get('delete/{id}', 'AddressController@delete');
        });

        // SocialNetwork
        Route::prefix('social-network')->group(function () {
            Route::get('/', 'SocialNetworkController@index')->name('admin.social-network.index');
            Route::post('tim-kiem', 'SocialNetworkController@search')->name('admin.social-network.search');
            Route::get('create', 'SocialNetworkController@create')->name('admin.social-network.create');
            Route::post('store', 'SocialNetworkController@store')->name('admin.social-network.store');
            Route::get('edit/{id}', 'SocialNetworkController@edit')->name('admin.social-network.edit');
            Route::post('update/{id}', 'SocialNetworkController@update')->name('admin.social-network.update');
            Route::post('update-status', 'SocialNetworkController@updateStatus');
            Route::get('delete/{id}', 'SocialNetworkController@delete');
        });

        // Letter
        Route::prefix('letter')->group(function () {
            Route::get('/', 'LetterController@index')->name('admin.letter.index');
            Route::post('tim-kiem', 'LetterController@search')->name('admin.letter.search');
            Route::get('delete/{id}', 'LetterController@delete');
        });

        // Intro
        Route::prefix('intro')->group(function () {
            Route::get('/', 'IntroController@index')->name('admin.intro.index');
            Route::post('tim-kiem', 'IntroController@search')->name('admin.intro.search');
            Route::get('create', 'IntroController@create')->name('admin.intro.create');
            Route::post('store', 'IntroController@store')->name('admin.intro.store');
            Route::get('edit/{id}', 'IntroController@edit')->name('admin.intro.edit');
            Route::post('update/{id}', 'IntroController@update')->name('admin.intro.update');
            Route::post('update-status', 'IntroController@updateStatus');
            Route::get('delete/{id}', 'IntroController@delete');
        });

        // Member
        Route::prefix('member')->group(function () {
            Route::get('/', 'MemberController@index')->name('admin.member.index');
            Route::post('tim-kiem', 'MemberController@search')->name('admin.member.search');
            Route::get('create', 'MemberController@create')->name('admin.member.create');
            Route::post('store', 'MemberController@store')->name('admin.member.store');
            Route::get('edit/{id}', 'MemberController@edit')->name('admin.member.edit');
            Route::post('update/{id}', 'MemberController@update')->name('admin.member.update');
            Route::post('update-status', 'MemberController@updateStatus');
            Route::get('delete/{id}', 'MemberController@delete');
        });

        // SongPowerPoint
        Route::prefix('song-power-point')->group(function () {
            Route::get('/', 'SongPowerPointController@index')->name('admin.song-power-point.index');
            Route::post('tim-kiem', 'SongPowerPointController@search')->name('admin.song-power-point.search');
            Route::get('create', 'SongPowerPointController@create')->name('admin.song-power-point.create');
            Route::post('store', 'SongPowerPointController@store')->name('admin.song-power-point.store');
            Route::get('edit/{id}', 'SongPowerPointController@edit')->name('admin.song-power-point.edit');
            Route::post('update/{id}', 'SongPowerPointController@update')->name('admin.song-power-point.update');
            Route::post('update-status', 'SongPowerPointController@updateStatus');
            Route::get('delete/{id}', 'SongPowerPointController@delete');
        });

        // WeeklyPowerPoint
        Route::prefix('weekly-power-point')->group(function () {
            Route::get('/', 'WeeklyPowerPointController@index')->name('admin.weekly-power-point.index');
            Route::post('tim-kiem', 'WeeklyPowerPointController@search')->name('admin.weekly-power-point.search');
            Route::get('create', 'WeeklyPowerPointController@create')->name('admin.weekly-power-point.create');
            Route::post('store', 'WeeklyPowerPointController@store')->name('admin.weekly-power-point.store');
            Route::get('edit/{id}', 'WeeklyPowerPointController@edit')->name('admin.weekly-power-point.edit');
            Route::post('update/{id}', 'WeeklyPowerPointController@update')->name('admin.weekly-power-point.update');
            Route::post('update-status', 'WeeklyPowerPointController@updateStatus');
            Route::get('delete/{id}', 'WeeklyPowerPointController@delete');
        });

        // News
        Route::prefix('news')->group(function () {
            Route::get('/', 'NewsController@index')->name('admin.news.index');
            Route::post('tim-kiem', 'NewsController@search')->name('admin.news.search');
            Route::get('create', 'NewsController@create')->name('admin.news.create');
            Route::post('store', 'NewsController@store')->name('admin.news.store');
            Route::get('edit/{id}', 'NewsController@edit')->name('admin.news.edit');
            Route::post('update/{id}', 'NewsController@update')->name('admin.news.update');
            Route::post('update-status', 'NewsController@updateStatus');
            Route::get('delete/{id}', 'NewsController@delete');
        });

        // Announcements
        Route::prefix('announcements')->group(function () {
            Route::get('/', 'AnnouncementsController@index')->name('admin.announcements.index');
            Route::post('tim-kiem', 'AnnouncementsController@search')->name('admin.announcements.search');
            Route::get('create', 'AnnouncementsController@create')->name('admin.announcements.create');
            Route::post('store', 'AnnouncementsController@store')->name('admin.announcements.store');
            Route::get('edit/{id}', 'AnnouncementsController@edit')->name('admin.announcements.edit');
            Route::post('update/{id}', 'AnnouncementsController@update')->name('admin.announcements.update');
            Route::post('update-status', 'AnnouncementsController@updateStatus');
            Route::get('delete/{id}', 'AnnouncementsController@delete');
        });

        // OriginalBibleVerse
        Route::prefix('original-bible-verse')->group(function () {
            Route::get('/', 'OriginalBibleVerseController@index')->name('admin.original-bible-verse.index');
            Route::post('tim-kiem', 'OriginalBibleVerseController@search')->name('admin.original-bible-verse.search');
            Route::get('create', 'OriginalBibleVerseController@create')->name('admin.original-bible-verse.create');
            Route::post('store', 'OriginalBibleVerseController@store')->name('admin.original-bible-verse.store');
            Route::get('edit/{id}', 'OriginalBibleVerseController@edit')->name('admin.original-bible-verse.edit');
            Route::post('update/{id}', 'OriginalBibleVerseController@update')->name('admin.original-bible-verse.update');
            Route::post('update-status', 'OriginalBibleVerseController@updateStatus');
            Route::get('delete/{id}', 'OriginalBibleVerseController@delete');
        });

        // Album
        Route::prefix('album')->group(function () {
            Route::get('/', 'AlbumController@index')->name('admin.album.index');
            Route::post('tim-kiem', 'AlbumController@search')->name('admin.album.search');
            Route::get('create', 'AlbumController@create')->name('admin.album.create');
            Route::post('store', 'AlbumController@store')->name('admin.album.store');
            Route::get('edit/{id}', 'AlbumController@edit')->name('admin.album.edit');
            Route::post('update/{id}', 'AlbumController@update')->name('admin.album.update');
            Route::post('update-status', 'AlbumController@updateStatus');
            Route::get('delete/{id}', 'AlbumController@delete');

            Route::get('{album_id}/album-image', 'AlbumImageController@index')->name('admin.album-image.index');
            Route::post('{album_id}/album-image-store', 'AlbumImageController@store')->name('admin.album-image.store');
            Route::post('album-image-update-status', 'AlbumImageController@updateStatus');
            Route::get('album-image-delete/{id}', 'AlbumImageController@delete');
        });

        // AdminUser
        Route::prefix('admin-user')->group(function () {
            Route::get('/', 'AdminUserController@index')->name('admin.admin-user.index');
            Route::post('tim-kiem', 'AdminUserController@search')->name('admin.admin-user.search');
            Route::get('create', 'AdminUserController@create')->name('admin.admin-user.create');
            Route::post('store', 'AdminUserController@store')->name('admin.admin-user.store');
            Route::post('update-status', 'AdminUserController@updateStatus');
            Route::get('delete/{id}', 'AdminUserController@delete');


            // --------------------
            Route::get('personal-page', 'AdminUserController@personalPage')->name('admin.admin-user.personal-page');
            Route::post('change-information/{id}', 'AdminUserController@changeInformation')->name('admin.admin-user.change-information');
            Route::post('change-password/{id}', 'AdminUserController@changePassword')->name('admin.admin-user.change-password');
        });
    });
});
