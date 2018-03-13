<?php 

namespace Runsite\CMF\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Auth;
use Route;
use Runsite\CMF\Models\Dynamic\Language;
use LaravelLocalization;
use Runsite\CMF\Models\Notification;
use Runsite\CMF\Models\SearchHistory;
use Runsite\CMF\Models\Application;

class AppComposer {

	protected $authUser = null;

	protected $apps = [];

	protected $allLanguages = [];

	protected $languagesHaveErrors = false;

	protected $notifications = [];

	protected $unreadNotificationsCount = 0;

	protected $searchHistory = [];

	protected $treeApplications = [];

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

		$this->notifications = Notification::where('user_id', Auth::id())->orderBy('is_reviewed', 'asc')->orderBy('created_at', 'desc')->take(10)->get();

		$this->unreadNotificationsCount =  Notification::where('user_id', Auth::id())->where('is_reviewed', false)->count();

		Notification::where('user_id', Auth::id())->where('is_sounded', false)->update([
			'is_sounded' => true,
		]);

		$this->searchHistory = SearchHistory::where('user_id', Auth::id())->orderBy('created_at', 'desc')->take(15)->get();

		$this->treeApplications = Application::where('is_tool', true)->get();
	}
	/**
	 * Bind data to the view.
	 *
	 * @param  View  $view
	 * @return void
	 */
	public function compose(View $view)
	{
		$view->with('authUser', $this->authUser)->with('allLanguages', $this->allLanguages)->with('languagesHaveErrors', $this->languagesHaveErrors)->with('notifications', $this->notifications)->with('unreadNotificationsCount', $this->unreadNotificationsCount)->with('searchHistory', $this->searchHistory)->with('treeApplications', $this->treeApplications);
	}
}
