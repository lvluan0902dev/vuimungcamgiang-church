<?php

use Illuminate\Support\Facades\Route;

Route::prefix('/')->namespace('Client')->group(function () {
    Route::get('/', 'HomeController@index')->name('client.home.index');

    // Contact
    Route::get('lien-he', 'ContactController@index')->name('client.contact.index');
    Route::post('letter-post', 'ContactController@letterPost')->name('client.contact.letter-post');

    // About
    Route::get('gioi-thieu', 'AboutController@index')->name('client.about.index');

    // PowerPoint
    Route::get('xem-power-point-bai-hat/{file_name}', 'PowerPointController@viewSongPowerPoint')->name('client.power-point.view-song-power-point');
    Route::get('xem-power-point-hang-tuan/{file_name}', 'PowerPointController@viewWeeklyPowerPoint')->name('client.power-point.view-weekly-power-point');

    // Song PowerPoint
    Route::get('power-point-bai-hat', 'SongPowerPointController@index')->name('client.song-power-point.index');
    Route::post('power-point-bai-hat/tim-kiem', 'SongPowerPointController@search')->name('client.song-power-point.search');
    Route::post('power-point-bai-hat/update-number-of-downloads', 'SongPowerPointController@updateNumberOfDownloads');

    // Weekly PowerPoint
    Route::get('power-point-hang-tuan', 'WeeklyPowerPointController@index')->name('client.weekly-power-point.index');
    Route::post('power-point-hang-tuan/tim-kiem', 'WeeklyPowerPointController@search')->name('client.weekly-power-point.search');
    Route::post('power-point-hang-tuan/update-number-of-downloads', 'WeeklyPowerPointController@updateNumberOfDownloads');

    // News
    Route::get('tin-tuc', 'NewsController@index')->name('client.news.index');
    Route::get('tin-tuc/{url}', 'NewsController@details')->name('client.news.details');
    Route::post('tin-tuc/tim-kiem', 'NewsController@search')->name('client.news.search');

    // Announcements
    Route::get('thong-bao', 'AnnouncementsController@index')->name('client.announcements.index');
    Route::get('thong-bao/{url}', 'AnnouncementsController@details')->name('client.announcements.details');
    Route::post('thong-bao/tim-kiem', 'AnnouncementsController@search')->name('client.announcements.search');

    // OriginalBibleVerse
    Route::get('cau-goc-kinh-thanh', 'OriginalBibleVerseController@index')->name('client.original-bible-verse.index');
    Route::get('cau-goc-kinh-thanh/{url}', 'OriginalBibleVerseController@details')->name('client.original-bible-verse.details');
    Route::post('cau-goc-kinh-thanh/tim-kiem', 'OriginalBibleVerseController@search')->name('client.original-bible-verse.search');

    // Album
    Route::get('album', 'AlbumController@index')->name('client.album.index');
    Route::get('album/{url}', 'AlbumController@details')->name('client.album.details');
    Route::post('album/tim-kiem', 'AlbumController@search')->name('client.album.search');
});
