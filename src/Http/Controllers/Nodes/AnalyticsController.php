<?php

namespace Runsite\CMF\Http\Controllers\Nodes;

use Illuminate\Http\Request;
use Runsite\CMF\Http\Controllers\BaseAdminController;
use Runsite\CMF\Models\Node\Node;
use Runsite\CMF\Models\Model\Model;
use Auth;
use Carbon\Carbon;

class AnalyticsController extends BaseAdminController
{
	protected $groupBy = [
		50 => 'days',
		1500 => 'months',
	];

	public function show(Node $node, Model $depended_model)
	{
		$breadcrumbs = $node->breadcrumbs();
		$depended_models = $node->dependedModels($depended_model);

		$nodes = Node::where('parent_id', $node->id)->where('model_id', $depended_model->id);

		if(request('date_start') and request('date_end'))
		{
			$nodes = $nodes->where('created_at', '>=', request('date_start'));
			$nodes = $nodes->where('created_at', '<=', request('date_end'));

			$date_start = Carbon::parse(request('date_start'));
			$date_end = Carbon::parse(request('date_end'));
		}

		else
		{
			$date_start = Node::orderBy('created_at', 'asc')->first()->created_at;
			// $date_end = Node::orderBy('created_at', 'desc')->first()->created_at;
			$date_end = Carbon::now();
		}

		$diffrent = $date_start->diffInMonths($date_end);

		// $nodes = $nodes->get()->groupBy(function($item) {
		// 	return Carbon::parse($item->created_at)->format('Y-m-d');
		// });

		$data = [];
		$points = [];
		for($i=0; $i<$diffrent; $i++)
		{
			$day = $date_start->copy()->addMonth($i);
			// if(isset($nodes[$day->format('Y-m-d')]))
			// {
			// 	$data[] = $nodes[$day->format('Y-m-d')]->count();
			// }
			// else 
			// {
			// 	$data[] = 0;
			// }ґ

			$tmp = $nodes;

			$data[] = Node::where('parent_id', $node->id)->where('model_id', $depended_model->id)->where('created_at', 'like', $day->format('Y-m').'%')->count();

			// echo $day.'<br>';

			$points[] = $day->format('Y-m');
			// $data[] = $nodes[$day]->count();
		}

		// dd($data);


		
		$chartjs = app()->chartjs
				->name('lineChartTest')
				->type('line')
				->size(['width' => 400, 'height' => 100])
				->labels($points)
				->datasets([
					[
						"label" => "My First dataset",
						'backgroundColor' => "rgba(52, 127, 251, 0.1)",
						'borderColor' => "#347ffb",
						"pointBorderColor" => "#347ffb",
						"pointBackgroundColor" => "#fff",
						"pointHoverBackgroundColor" => "#fff",
						"pointHoverBorderColor" => "#347ffb",
						'data' => $data,
					]
				])
				->options([]);

				// dd($chartjs);
		return view('runsite::nodes.analytics.show', compact('node', 'depended_model', 'breadcrumbs', 'depended_models', 'depended_model', 'chartjs'));
	}
}
