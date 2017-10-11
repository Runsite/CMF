<?php

namespace Runsite\CMF\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

class BaseAdminController extends BaseController
{
    use ValidatesRequests;

    public function __construct()
    {
        
    }
}
