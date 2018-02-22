<?php

namespace Runsite\CMF\Http\Controllers\Model;

use Illuminate\Http\Request;
use Runsite\CMF\Http\Controllers\BaseAdminController;
use Runsite\CMF\Models\Model\Model;
use Runsite\CMF\Validator\Rules\ValidMethod;
use Runsite\CMF\Traits\Applicable;

class MethodsController extends BaseAdminController
{
	use Applicable;

	protected $application_name = 'models';

	public function __boot()
	{
		$this->middleware('application-access:models:edit')->only('update');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Model $model)
	{
		$methods = $model->methods;
		return view('runsite::models.methods.edit', compact('model', 'methods'))->withApplication($this->application);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Model  $model
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Model $model)
	{
		$data = $request->validate([
			'get' => ['nullable', 'string', new ValidMethod],
			'post' => ['nullable', 'string', new ValidMethod],
			'patch' => ['nullable', 'string', new ValidMethod],
			'delete' => ['nullable', 'string', new ValidMethod],
		]);

		$methods = $model->methods->update($data);
		return redirect()->route('admin.models.methods.edit', $model)->with('success', trans('runsite::models.methods.The model methods are updated'));
	}

	public function controller(Model $model, $controller)
	{
		$content = file_get_contents(app_path('Http/Controllers/'.$controller.'.php'));
		$methods = $model->methods;
		return view('runsite::models.methods.controller', compact('content', 'methods', 'model'))->with('controller_name', $controller);
	}
}
