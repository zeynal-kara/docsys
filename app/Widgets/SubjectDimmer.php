<?php

namespace App\Widgets;

use Illuminate\Support\Facades\Auth;
use TCG\Voyager\Widgets\BaseDimmer;
use App\Models\Subject;

class SubjectDimmer extends BaseDimmer
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
        $count = Subject::count();
        $string = "Subjects";

        return view('voyager::dimmer', array_merge($this->config, [
            'icon'   => 'voyager-milestone',
            'title'  => "{$count} {$string}",
            'text'   => ' You have '. $count .' subjects in your database. Click on button below to view all.',
            'button' => [
                'text' => 'View Subjects',
                'link' => '/admin/subjects',
            ],
            'image' => voyager_asset('images/widget-backgrounds/02.jpg'),
        ]));
    }

    /**
     * Determine if the widget should be displayed.
     *
     * @return bool
     */
    public function shouldBeDisplayed()
    {
        return Auth::user()->hasPermission('browse_subjects');
    }

    
}
