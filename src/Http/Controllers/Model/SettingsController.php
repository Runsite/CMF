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
    public function edit(Model $model)
    {
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
    public function update(Request $request, Model $model)
    {
        $data = $request->validate([
            'show_in_admin_tree' => 'nullable|boolean',
            'nodes_ordering' => 'required|string|max:255',
            'dynamic_model' => 'required|string|max:255',
        ]);

        $model->settings->update($data);
        return redirect()->route('admin.models.settings.edit', $model)->with('success', trans('runsite::models.settings.The model settings is updated'));
    }
}
