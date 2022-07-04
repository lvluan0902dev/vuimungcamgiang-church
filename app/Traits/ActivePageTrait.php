<?php

namespace App\Traits;

use Illuminate\Support\Facades\Session;

trait ActivePageTrait {
    public function setAdminPage($page, $item) {
        Session::put('page_admin', $page);
        Session::put('item_admin', $item);
    }

    public function setClientPage($page, $item) {
        Session::put('page_client', $page);
        Session::put('item_client', $item);
    }
}
