<?php

namespace Runsite\CMF\Http\Controllers\Model;

use Illuminate\Http\Request;
use Runsite\CMF\Http\Controllers\BaseAdminController;
use Runsite\CMF\Models\Model\Model;
use Runsite\CMF\Traits\Applicable;

class SettingsController extends BaseAdminController
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
    public function edit($model_id)
    {
        $model = Model::findOrFail($model_id);
        $settings = $model->settings;
        return view('runsite::models.settings.edit', compact('model', 'settings'))->withApplication($this->application);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model  $model
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $model_id)
    {
        $data = $request->validate([
            'show_in_admin_tree' => 'nullable|integer',
            'nodes_ordering' => 'required|string|max:255',
            'dynamic_model' => 'required|string|max:255',
        ]);

        $model = Model::findOrFail($model_id)->settings->update($data);
        return redirect()->route('admin.models.settings.edit', $model_id)->with('success', trans('runsite::models.settings.The model settings is updated'));
    }
}
