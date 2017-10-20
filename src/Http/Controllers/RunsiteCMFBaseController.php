<?php

namespace Runsite\CMF\Http\Controllers;

use Illuminate\{
    Routing\Controller as BaseController,
    Foundation\Validation\ValidatesRequests
};
use Runsite\CMF\Helpers\GlobalScope;

class RunsiteCMFBaseController extends BaseController
{
    use ValidatesRequests;

    protected $node = null;
    protected $fields = null;

    public function __construct()
    {
        $scope = new GlobalScope;

        $this->node = $scope->get('_runsite_cmf_node_');

        if($this->node)
        {
            $this->fields = M($this->node->model->name)->where('node_id', $this->node->id)->first();
        }

        if(isset($this->fields->is_active) and !$this->fields->is_active)
        {
            // Aborting request, because "is_active" parameter exists and is false
            return abort(404);
        }
    }

    public function view($view, $params=null)
    {
        $p = [
            'node'     => $this->node,
            'fields'   => $this->fields,
        ];

        if($params) {
            $p = array_merge($p, $params);
        }

        return view($view, $p);
    }
}
