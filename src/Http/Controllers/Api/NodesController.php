<?php

namespace Runsite\CMF\Http\Controllers\Api;

use Illuminate\Http\Request;
use Runsite\CMF\Http\Controllers\BaseAdminController;

class NodesController extends BaseAdminController
{

    public function findByName(Request $request)
    {
        $key = $request->input('q')['term'] ?? null;
        $related_model_name = $request->input('related_model_name');
        $related_parent_node_id = $request->input('related_parent_node_id');
        $results = [];

        if(! $request->multiple)
        {
            $results[] = [
                'id' => '',
                'text' => '---',
            ];
        }

        $nodes = M($related_model_name, true, config('app.fallback_locale'));

        if($key)
        {
            $nodes = $nodes->where('name', 'like', '%'.$key.'%');
        }
        else
        {
            $nodes = $nodes->take(15);
        }

        $nodes = $nodes->orderBy($related_model_name.'.created_at', 'desc')->get();
        

        foreach($nodes as $node)
        {
            $results[] = [
                'id' => $node->node_id,
                'text' => $node->name,
            ];
        }

        if(((count($results) == 1 and !$request->multiple) or (count($results) == 0 and $request->multiple)) and $related_parent_node_id)
        {
            $results[] = [
                'id' => '@#-create-' . $key,
                'text' => trans('runsite::nodes.Create').': "'.$key.'"',
            ];
        }

        return response()->json([
          'results' => $results
        ]);
    }
}
