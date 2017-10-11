<?php 

namespace Runsite\CMF\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Auth;
use Route;
use LaravelLocalization;

class AppComposer {

    protected $authUser = null;

    protected $apps = [];

    /**
     * Create a new app composer.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authUser = Auth::user();
    }
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('authUser', $this->authUser);
    }
}
