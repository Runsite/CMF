<?php 

use Runsite\CMF\Models\Translation;
use Runsite\CMF\Models\Dynamic\Language;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Goszowski\Temp\Temp;

function t($key)
{
    $currentLocale = LaravelLocalization::getCurrentLocale();
    $cacheName = 'translation_' . md5($currentLocale . $key);

    $value = Temp::get($cacheName);

    if(! $value)
    {
        $language = Language::where('locale', $currentLocale)->first();
        $translation = Translation::where('key', $key)->where('language_id', $language->id)->first();

        if(! $translation)
        {
            foreach(Language::get() as $language)
            {
                $existingTranslation = (bool) Translation::where('language_id', $language->id)->where('key', $key)->count();
                if(! $existingTranslation)
                {
                    Translation::create([
                        'language_id' => $language->id,
                        'key' => $key,
                        'value' => $key,
                    ]);
                }
            }

            return t($key);
        }

        Temp::put($cacheName, $translation->value);
    }

    return Temp::get($cacheName);
}
