<?php

namespace Runsite\CMF\Http\Controllers\Nodes\Settings;

use Illuminate\Http\Request;
use Runsite\CMF\Http\Controllers\BaseAdminController;
use Runsite\CMF\Models\Node\Node;
use Runsite\CMF\Validator\Rules\ValidMethod;
use Runsite\CMF\Traits\Applicable;

class SettingsController extends BaseAdminController
{
    use Applicable;

    protected $application_name = 'nodes';

    public function __boot()
    {
        $this->middleware('application-access:nodes:edit')->only('update');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Node $node)
    {
        $settings = $node->settings;
        $breadcrumbs = $node->breadcrumbs();
        return view('runsite::nodes.settings.edit', compact('node', 'settings', 'breadcrumbs'))->withApplication($this->application);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model  $model
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Node $node)
    {
        $data = $request->validate([
            'use_response_cache' => 'nullable|boolean',
            'node_icon' => 'nullable|string',
        ]);

        $node->settings->update($data);
        return redirect()->route('admin.nodes.settings.edit', $node)->with('success', trans('runsite::nodes.settings.The node settings are updated'));
    }
}
