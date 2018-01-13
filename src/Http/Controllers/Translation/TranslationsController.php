<?php

namespace Runsite\CMF\Http\Controllers\Translation;

use Illuminate\Http\Request;
use Runsite\CMF\Http\Controllers\BaseAdminController;
use Runsite\CMF\Models\Translation;
use Runsite\CMF\Models\Dynamic\Language;
use Runsite\CMF\Traits\Applicable;

class TranslationsController extends BaseAdminController
{
    use Applicable;

    protected $application_name = 'translations';

    public function __boot()
    {
        $this->middleware('application-access:translations:edit')->only(['update', 'edit']);
        $this->middleware('application-access:translations:delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $translations = Translation::groupBy('key');

        if(request('search'))
        {
            $translations = $translations
                ->where('key', 'like', '%'.request('search').'%')
                ->orWhere('value', 'like', '%'.request('search').'%');
        }

        $translations = $translations->paginate();

        return view('runsite::translations.index', compact('translations'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Translation $translation)
    {
        $languages = Language::get();
        $translations = $translation->variants();
        return view('runsite::translations.edit', compact('translation', 'languages', 'translations'))->withApplication($this->application);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model  $model
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Translation $translation)
    {
        foreach(request('values') as $language_id=>$value)
        {
            $existingTranslation = Translation::where('language_id', $language_id)->where('key', $translation->key)->first();

            if($existingTranslation)
            {
                $existingTranslation->value = $value;
                $existingTranslation->save();
            }
            else
            {
                Translation::create([
                    'language_id' => $language_id,
                    'key' => $translation->key,
                    'value' => $value,
                ]);
            }
        }

        return redirect()->route('admin.translations.index')->with('success', trans('runsite::translations.The translation is updated'));
    }
}
