<?php

namespace App\Widgets;

use Illuminate\Support\Facades\Auth;
use TCG\Voyager\Widgets\BaseDimmer;
use App\Models\IpAddress;

class IpAddressDimmer extends BaseDimmer
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $count = IpAddress::count();
        $string = "Ip Address";

        return view('voyager::dimmer', array_merge($this->config, [
            'icon'   => 'voyager-eye',
            'title'  => "{$count} {$string}",
            'text'   => ' You have '. $count .' ip addresses in your database. Click on button below to view all.',
            'button' => [
                'text' => 'View Ip Addresses',
                'link' => '/admin/ip-addresses',
            ],
            'image' => asset('images/ip-address.jpg'),
        ]));
    }

    /**
     * Determine if the widget should be displayed.
     *
     * @return bool
     */
    public function shouldBeDisplayed()
    {
        return Auth::user()->hasPermission('browse_ip_addresses');
    }

    
}
