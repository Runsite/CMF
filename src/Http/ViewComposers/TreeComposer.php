<?php 

namespace Runsite\CMF\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Runsite\CMF\Models\Node\Node;

class TreeComposer {

    protected $rootNode = null;
    protected $childNodes = null;

    /**
     * Create a new app composer.
     *
     * @return void
     */
    public function __construct()
    {
        $this->rootNode = Node::findOrFail(1);

        $this->childNodes = $this->getChildren($this->rootNode);

        // dd($this->children);
    }
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('rootNode', $this->rootNode)->with('childNodes', $this->childNodes);
    }

    protected function getChildren(Node $parentNode)
    {
        // $depended_models = [];

        // foreach($parentNode->model->dependencies as $dependency)
        // {
        //     $depended_models[$dependency->id] = $dependency->id;
        // }

        // foreach($parentNode->dependencies as $dependency)
        // {
        //     $depended_models[$dependency->id] = $dependency->id;
        // }


        // $children = Node::where('parent_id', $parentNode->id)->whereIn('model_id', $depended_models)->orderBy('position', 'asc')->get();

        // return $children;

        $nodes = Node::select('rs_nodes.id', 'rs_nodes.model_id', 'rs_nodes.parent_id')
        ->join('rs_models', 'rs_models.id', '=', 'rs_nodes.model_id')
        ->join('rs_model_settings', 'rs_model_settings.model_id', '=', 'rs_models.id')
        ->where('rs_model_settings.show_in_admin_tree', 1)
        ->where('rs_nodes.parent_id', 1)
        ->with('currentLanguagePath')
        ->get();


        return $nodes;
    }
}
