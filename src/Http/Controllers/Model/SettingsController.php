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
            'dynamic_model' => 'nullable|string|max:255',
            'use_response_cache' => 'nullable|boolean',
            'slug_autogeneration' => 'nullable|boolean',
            'max_nodes_count' => 'nullable|integer',
            'node_icon' => 'nullable|string',
            'is_searchable' => 'nullable|boolean',
            'redirect_to_node_after_creation' => 'nullable|boolean',
            'require_seo' => 'nullable|boolean',
        ]);

        $model->settings->update($data);
        return redirect()->route('admin.models.settings.edit', $model)->with('success', trans('runsite::models.settings.The model settings is updated'));
    }

    public function makeModelSearchable(Model $model)
    {
        $model->settings->is_searchable = true;
        $model->settings->save();

        return redirect()->back();
    }
}
