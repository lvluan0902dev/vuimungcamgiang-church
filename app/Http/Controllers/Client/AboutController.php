<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Model\Intro;
use App\Model\Member;
use App\Traits\ActivePageTrait;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    use ActivePageTrait;

    private $intro;
    private $redisTime;
    private $member;

    public function __construct(Intro $intro, Member $member)
    {
        $this->intro = $intro;
        $this->member = $member;
        $this->redisTime = config('app.redis_time');
    }

    public function index() {
        $this->setClientPage('about', 'about_index');
        $intros = \Cache::remember('vmcgc_client_intros', $this->redisTime, function () {
            return $this->intro->where('status', 1)->orderBy('order', 'ASC')->get();
        });

        $members = \Cache::remember('vmcgc_client_members', $this->redisTime, function () {
            return $this->member->where('status', 1)->orderBy('order', 'ASC')->get();
        });

        return view('client.components.about.index')->with(compact('intros', 'members'));
    }
}
