<?php

namespace Runsite\CMF\Http\Controllers\Nodes\Settings;

use Illuminate\Http\Request;
use Runsite\CMF\Http\Controllers\BaseAdminController;
use Runsite\CMF\Models\Node\Node;
use Runsite\CMF\Validator\Rules\ValidMethod;
use Runsite\CMF\Traits\Applicable;

class MethodsController extends BaseAdminController
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
        $methods = $node->methods;
        $breadcrumbs = $node->breadcrumbs();
        return view('runsite::nodes.settings.methods.edit', compact('node', 'methods', 'breadcrumbs'))->withApplication($this->application);
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
            'get' => ['nullable', 'string', new ValidMethod],
            'post' => ['nullable', 'string', new ValidMethod],
            'patch' => ['nullable', 'string', new ValidMethod],
            'delete' => ['nullable', 'string', new ValidMethod],
        ]);

        $methods = $node->methods->update($data);
        return redirect()->route('admin.nodes.settings.methods.edit', $node)->with('success', trans('runsite::nodes.methods.The node methods are updated'));
    }

    public function controller(Node $node, $controller)
    {
        $content = file_get_contents(app_path('Http/Controllers/'.$controller.'.php'));
        $methods = $node->methods;
        $breadcrumbs = $node->breadcrumbs();
        return view('runsite::nodes.settings.controller', compact('content', 'methods', 'node', 'breadcrumbs'))->with('controller_name', $controller);
    }
}
