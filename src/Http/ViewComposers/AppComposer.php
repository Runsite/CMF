<?php 

namespace Runsite\CMF\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Auth;
use Route;
use Runsite\CMF\Models\Dynamic\Language;
use LaravelLocalization;

class AppComposer {

    protected $authUser = null;

    protected $apps = [];

    protected $allLanguages = [];

    protected $languagesHaveErrors = false;

    /**
     * Create a new app composer.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authUser = Auth::user();
        $this->allLanguages = Language::get();

        foreach(LaravelLocalization::getSupportedLocales() as $locale=>$info)
        {
            if(! $this->allLanguages->where('locale', $locale)->count())
            {
                $this->languagesHaveErrors = true;
            }
        }

        if(! $this->languagesHaveErrors)
        {
            foreach($this->allLanguages as $language)
            {
                if(! $language->hasConfig())
                {
                    $this->languagesHaveErrors = true;
                }
            }
        }
    }
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('authUser', $this->authUser)->with('allLanguages', $this->allLanguages)->with('languagesHaveErrors', $this->languagesHaveErrors);
    }
}
