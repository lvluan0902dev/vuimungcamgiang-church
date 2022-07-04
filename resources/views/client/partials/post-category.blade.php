<?php
use App\Model\NewsAndAnnouncements;
use App\Model\OriginalBibleVerse;

$redisTime = config('app.redis_time');

$totalNews = \Cache::remember('vmcgc_client_total_news', $redisTime, function () {
    return NewsAndAnnouncements::where([['type', 'news'], ['status', 1]])->get()->count();
});

$totalAnnouncements = \Cache::remember('vmcgc_client_total_announcements', $redisTime, function () {
    return NewsAndAnnouncements::where([['type', 'announcements'], ['status', 1]])->get()->count();
});

$totalOriginalBibleVerse = \Cache::remember('vmcgc_client_total_original_bible_verse', $redisTime, function () {
    return OriginalBibleVerse::where('status', 1)->get()->count();
});
?>

<!-- Sidebar Widget Category Start -->
<div class="sidebar-widget">
    <h4 class="widget-title">Danh mục Bài viết</h4>

    <div class="widget-category">
        <ul class="category-list">
            <li><a href="{{ route('client.news.index') }}">Tin tức <span>({{ number_format($totalNews) }})</span></a></li>
            <li><a href="{{ route('client.announcements.index') }}">Thông báo <span>({{ number_format($totalAnnouncements) }})</span></a></li>
            <li><a href="{{ route('client.original-bible-verse.index') }}">Câu gốc Kinh Thánh <span>({{ number_format($totalOriginalBibleVerse) }})</span></a></li>
        </ul>
    </div>
</div>
<!-- Sidebar Widget Category End -->
