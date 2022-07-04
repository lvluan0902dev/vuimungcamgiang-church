<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Model\Address;
use App\Model\Email;
use App\Model\GoogleMaps;
use App\Model\Letter;
use App\Model\PhoneNumber;
use App\Traits\ActivePageTrait;
use App\Traits\RedisTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ContactController extends Controller
{
    use RedisTrait;
    use ActivePageTrait;

    private $googleMaps;
    private $phoneNumber;
    private $email;
    private $address;
    private $letter;
    private $redisTime;

    public function __construct(GoogleMaps $googleMaps, PhoneNumber $phoneNumber, Email $email, Address $address, Letter $letter)
    {
        $this->googleMaps = $googleMaps;
        $this->phoneNumber = $phoneNumber;
        $this->email = $email;
        $this->address = $address;
        $this->letter = $letter;
        $this->redisTime = config('app.redis_time');
    }

    public function index() {
        $this->setClientPage('contact', 'contact_index');
        $googleMaps = \Cache::remember('vmcgc_client_google_maps', $this->redisTime, function () {
            return $this->googleMaps->where('status', 1)->orderBy('order', 'ASC')->first();
        });
        $phoneNumber = \Cache::remember('vmcgc_client_phone_number', $this->redisTime, function () {
            return $this->phoneNumber->where('status', 1)->orderBy('order', 'ASC')->first();
        });
        $email = \Cache::remember('vmcgc_client_email', $this->redisTime, function () {
            return $this->email->where('status', 1)->orderBy('order', 'ASC')->first();
        });
        $address = \Cache::remember('vmcgc_client_address', $this->redisTime, function () {
            return $this->address->where('status', 1)->orderBy('order', 'ASC')->first();
        });
        return view('client.components.contact.index')->with(compact('googleMaps', 'phoneNumber', 'email', 'address'));
    }

    public function letterPost(Request $request)
    {
        $this->setClientPage('contact', 'contact_index');
        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'content' => 'required',
        ];

        $customMessages = [
            'name.required' => 'Vui lòng nhập Tên',
            'email.required' => 'Vui lòng nhập Email',
            'email.email' => 'Email không đúng định dạng',
            'content.required' => 'Vui lòng nhập Nội dung',
        ];

        $this->validate($request, $rules, $customMessages);

        $data = $request->all();

        $this->letter->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone_number' => !empty($data['phone_number']) ? $data['phone_number'] : '',
            'content' => $data['content'],
        ]);

        Session::flash('send_letter_success_message', 'Gửi thành công');
        // Clear all key in Redis
        $this->removeAllKey();
        return redirect()->back();
    }
}
