<?php

namespace Runsite\CMF\Http\Controllers\Nodes\Settings;

use Illuminate\Http\Request;
use Runsite\CMF\Http\Controllers\BaseAdminController;
use Runsite\CMF\Models\Model\Model;
use Runsite\CMF\Models\Model\Dependency as ModelDependency;
use Runsite\CMF\Models\Node\Dependency;
use Runsite\CMF\Models\Node\Node;

class DependenciesController extends BaseAdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Node $node)
    {
        $breadcrumbs = $node->breadcrumbs();
        $depended_models = Dependency::where('node_id', $node->id)->with('node')->orderBy('position', 'asc')->get();
        $depended_locked_models = ModelDependency::where('model_id', $node->model_id)->with('model')->orderBy('position', 'asc')->get();

        $available_models = Model::whereNotIn('id', $depended_models->pluck('depended_model_id'))->whereNotIn('id', $depended_locked_models->pluck('depended_model_id'))->where('id', '!=', 1)->latest()->get();

        

        return view('runsite::nodes.settings.dependencies.index', compact('available_models', 'depended_models', 'depended_locked_models', 'node', 'breadcrumbs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Node $node)
    {
        $data = $request->validate([
            'depended_model_id' => 'required|integer|exists:rs_models,id',
        ]);

        $data['node_id'] = $node->id;

        Dependency::create($data);
        return redirect()->route('admin.nodes.settings.dependencies.index', $node)->with('success', trans('runsite::nodes.dependencies.The node dependency is created'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model  $model
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Node $node)
    {
        $request->validate([
            'depended_model_id' => 'required|integer|exists:rs_models,id',
        ]);

        $dependency = Dependency::where('node_id', $node->id)->where('depended_model_id', $request->depended_model_id)->first();

        $dependency->delete();
        return redirect()->route('admin.nodes.settings.dependencies.index', $node)->with('success', trans('runsite::models..nodes.dependencies.The node dependency is deleted'));
    }

    /**
     * Move up the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function moveUp(Node $node, $id)
    {
        Dependency::findOrFail($id)->moveUp();
        return redirect()->route('admin.nodes.settings.dependencies.index', $node)->with('highlight', $id);
    }

    /**
     * Move down the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function moveDown(Node $node, $id)
    {
        Dependency::findOrFail($id)->moveDown();
        return redirect()->route('admin.nodes.settings.dependencies.index', $node)->with('highlight', $id);
    }
}
