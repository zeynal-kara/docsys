<?php

namespace App\Widgets;

use Illuminate\Support\Facades\Auth;
use TCG\Voyager\Widgets\BaseDimmer;
use App\Models\Category;

class CategoryDimmer extends BaseDimmer
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
        $count = Category::count();
        $string = "Categories";

        return view('voyager::dimmer', array_merge($this->config, [
            'icon'   => 'voyager-categories',
            'title'  => "{$count} {$string}",
            'text'   => ' You have '. $count .' categories in your database. Click on button below to view all.',
            'button' => [
                'text' => 'View Categories',
                'link' => '/admin/categories',
            ],
            'image' => voyager_asset('images/widget-backgrounds/03.jpg'),
        ]));
    }

    /**
     * Determine if the widget should be displayed.
     *
     * @return bool
     */
    public function shouldBeDisplayed()
    {
        return Auth::user()->hasPermission('browse_categories');
    }

    
}
