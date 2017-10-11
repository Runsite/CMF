<?php

use Runsite\CMF\Models\Dynamic\Dynamic;
use Runsite\CMF\Models\Dynamic\Language;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Goszowski\Temp\Temp;

function M($model_name, $is_active=true, $language_locale=null)
{
    $dynamic = new Dynamic($model_name);

    $dynamic = $dynamic->join('rs_nodes', 'rs_nodes.id', '=', $model_name.'.node_id');

    if($is_active)
    {
        $dynamic = $dynamic->where('is_active', true);
    }

    if(!$language_locale)
    {
        $language = Temp::get('current-language') ?: Temp::put('current-language', Language::where('locale', LaravelLocalization::getCurrentLocale())->first());
    }
    else 
    {
        $language = Language::where('locale', $language_locale)->first();
    }

    if(!$language)
    {
        throw new Exception("Language ".$language_locale." not exists");
        return null;
    }

    return $dynamic->where('language_id', $language->id);
}