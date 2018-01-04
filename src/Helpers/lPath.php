<?php

use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

function lPath($path, $locale=null, $attributes = array())
{
    return LaravelLocalization::getLocalizedURL($locale, $path, $attributes);
}
