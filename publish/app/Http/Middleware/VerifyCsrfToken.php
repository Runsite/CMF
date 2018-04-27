<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
	/**
	 * The URIs that should be excluded from CSRF verification.
	 *
	 * @var array
	 */
	protected $except = [
		//
	];

	public function __counstruct()
	{
		$this->except[] = '/'.config('runsite.cmf.admin_dirname').'/api/ckeditor/images-upload';
		parent::__construct();
	}
}
