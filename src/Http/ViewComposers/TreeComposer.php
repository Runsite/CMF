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
        $depended_models = [];

        foreach($parentNode->model->dependencies as $dependency)
        {
            $depended_models[$dependency->id] = $dependency->id;
        }

        foreach($parentNode->dependencies as $dependency)
        {
            $depended_models[$dependency->id] = $dependency->id;
        }


        $children = Node::where('parent_id', $parentNode->id)->whereIn('model_id', $depended_models)->get();

        return $children;
    }
}
