<?php

namespace Runsite\CMF\Http\Controllers;

use Illuminate\{
    Routing\Controller as BaseController,
    Foundation\Validation\ValidatesRequests
};
use Runsite\CMF\Helpers\GlobalScope;
use StdClass;
use Auth;

class RunsiteCMFBaseController extends BaseController
{
    use ValidatesRequests;

    protected $authUser;

    protected $node = null;
    protected $path = null;
    protected $fields = null;
    protected $seo = null;

    public function __construct()
    {
        $scope = new GlobalScope;

        $this->node = $scope->get('_runsite_cmf_node_') or abort(404);
        $this->path = $scope->get('_runsite_cmf_path_') or abort(404);

        if($this->node->currentLanguagePath->name != $this->path->name)
        {
            return redirect(lPath($this->node->currentLanguagePath->name))->send();
        }

        $this->fields = M($this->node->model->name, false)->where('node_id', $this->node->id)->first() or abort(404);

        $this->seo = new StdClass();

        $this->seo->title = $this->fields->title ?? ($this->fields->name ?? config('app.name'));
        $this->seo->description = $this->fields->description ?? null;
        $this->seo->author = config('app.name');
        $this->seo->image = isset($this->fields->image) ? $this->fields->image->max() : null;

        $this->fields->node = $this->node;
        $this->fields->seo = $this->seo;
    }

    public function view($view, $params=null)
    {
        if(!$this->fields->is_active)
        {
            if(!Auth::user() or request('mode') != 'preview')
            {
                // Aborting request, because "is_active" parameter exists and is false
                return abort(404);
            }
        }

        $p = [
            $this->node->model->name   => $this->fields,
        ];

        if($params) {
            $p = array_merge($p, $params);
        }

        return view($view, $p);
    }
}
