<?php

namespace Runsite\CMF\Http\Controllers\Language;

use Illuminate\Http\Request;
use Runsite\CMF\Http\Controllers\BaseAdminController;
use Runsite\CMF\Models\Dynamic\Language;
use Runsite\CMF\Traits\Applicable;
use LaravelLocalization;

class LanguagesController extends BaseAdminController
{
	use Applicable;

	protected $application_name = 'languages';

	public function __boot()
	{
		$this->middleware('application-access:languages:edit')->only(['update', 'store', 'create']);
		$this->middleware('application-access:languages:delete')->only(['destroy']);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$languages = Language::paginate();
		return view('runsite::languages.index', compact('languages'))->withApplication($this->application);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create(Language $language)
	{
		return view('runsite::languages.create', compact('language'))->withApplication($this->application);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$data = $request->validate([
			'locale' => 'required|string|max:2|unique:rs_languages',
			'display_name' => 'required|string|max:255',
			'is_active' => '',
			'is_main' => '',
		]);

		if($request->is_main)
		{
			Language::where('is_main', true)->update([
				'is_main' => false,
			]);
		}

		Language::create($data);
		return redirect()->route('admin.languages.index')->with('success', trans('runsite::languages.The language is created'));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Model  $model
	 * @return \Illuminate\Http\Response
	 */
	public function show(Language $language)
	{
		return abort(404);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Language $language)
	{
		return view('runsite::languages.edit', compact('language'))->withApplication($this->application);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Model  $model
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Language $language)
	{
		$data = $request->validate([
			'locale' => 'required|string|max:2|unique:rs_languages,locale,'.$language->id.',id',
			'display_name' => 'required|string|max:255',
			'is_active' => '',
			'is_main' => '',
		]);

		if($request->is_main and !$language->is_main)
		{
			Language::where('is_main', true)->update([
				'is_main' => false,
			]);
		}

		$language->update($data);
		return redirect()->route('admin.languages.edit', $language->id)->with('success', trans('runsite::languages.The language is updated'));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Model  $model
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Language $language)
	{
		if(LaravelLocalization::getCurrentLocale() == $language->locale)
		{
			return redirect()->back();
		}
		
		$language->delete();
		return redirect()->route('admin.languages.index')->with('success', trans('runsite::languages.The language is deleted'));
	}
}
