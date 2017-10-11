<?php

namespace Runsite\CMF\Helpers;

class GlobalScope
{
    protected $globalName = 'runsite_cmf_global_scope';

    public function set($name, $value)
    {
        $GLOBALS[$this->globalName][$name] = $value;
    }

    public function get($name)
    {
        return isset($GLOBALS[$this->globalName][$name]) ? $GLOBALS[$this->globalName][$name] : null;
    }

    public function destroy($name)
    {
        return $GLOBALS[$this->globalName][$name] = null;
    }
}
