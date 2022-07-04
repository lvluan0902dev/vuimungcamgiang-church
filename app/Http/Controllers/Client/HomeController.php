<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Traits\ActivePageTrait;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    use ActivePageTrait;

    public function index() {
        $this->setClientPage('home', 'home_index');
        return view('client.components.home.index');
    }
}
