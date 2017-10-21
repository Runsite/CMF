<?php

namespace Runsite\CMF\Http\Controllers;

use Illuminate\{
	Routing\Controller as BaseController,
	Foundation\Validation\ValidatesRequests
};

class BaseAdminController extends BaseController
{
    use ValidatesRequests;
}
