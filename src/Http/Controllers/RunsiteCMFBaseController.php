<?php

namespace Runsite\CMF\Http\Controllers;

use Illuminate\{
    Routing\Controller as BaseController,
    Foundation\Validation\ValidatesRequests
};
use Runsite\CMF\Helpers\GlobalScope;
use StdClass;

class RunsiteCMFBaseController extends BaseController
{
    use ValidatesRequests;

    protected $node = null;
    protected $fields = null;
    protected $seo = null;

    public function __construct()
    {
        $scope = new GlobalScope;

        $this->node = $scope->get('_runsite_cmf_node_');

        if($this->node)
        {
            $this->fields = M($this->node->model->name)->where('node_id', $this->node->id)->first();
        }

        if(!$this->fields or (isset($this->fields->is_active) and !$this->fields->is_active))
        {
            // Aborting request, because "is_active" parameter exists and is false
            return abort(404);
        }

        $this->seo = new StdClass();

        $this->seo->title = $this->fields->title ?? ($this->fields->name ?? config('app.name'));
        $this->seo->description = $this->fields->description ?? null;
        $this->seo->author = config('app.name');
        $this->seo->image = isset($this->fields->image) ? $this->fields->image->max() : null;
    }

    public function view($view, $params=null)
    {
        $p = [
            'node'     => $this->node,
            'fields'   => $this->fields,
            'seo'      => $this->seo,
        ];

        if($params) {
            $p = array_merge($p, $params);
        }

        return view($view, $p);
    }
}
