<?php

namespace Runsite\CMF\Http\Controllers\Api;

use Illuminate\Http\Request;
use Runsite\CMF\Http\Controllers\BaseAdminController;

class NodesController extends BaseAdminController
{

    public function findByName(Request $request)
    {
        $key = $request->input('q')['term'];
        $related_model_name = $request->input('related_model_name');
        $related_parent_node_id = $request->input('related_parent_node_id');
        $results = [];

        $nodes = M($related_model_name, true, config('app.fallback_locale'))->where('name', 'like', '%'.$key.'%')->orderBy($related_model_name.'.created_at', 'desc')->get();

        foreach($nodes as $node)
        {
            $results[] = [
                'id' => $node->node_id,
                'text' => $node->name,
            ];
        }

        return response()->json([
          'results' => $results
        ]);
    }
}
