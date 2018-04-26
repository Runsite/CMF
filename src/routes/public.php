<?php 

use Runsite\CMF\Models\Node\Path;
use Runsite\CMF\Helpers\GlobalScope;
//, 'middleware' => ['localize', 'localeSessionRedirect', 'localizationRedirect' ]

if(! str_is('*.*', \Request::path()))
{
	Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['web', 'localize', 'localeSessionRedirect', 'localizationRedirect' ], 'namespace' => 'App\Http\Controllers'], function() {

		$requestPath = str_replace(\Request::root(), '', LaravelLocalization::getNonLocalizedURL(\Request::path()));
		if(! $requestPath)
		{
			$requestPath = '/';
		}

		$path = Path::where('name', $requestPath)->first();

		if($path)
		{
			$scope = new GlobalScope;

			$scope->set('_runsite_cmf_node_', $path->node);
			$scope->set('_runsite_cmf_path_', $path);

			if($path->node->methods->get)
			{
				if($path->node->settings->use_response_cache)
				{
					Route::get('/', ['middleware'=>['cacheResponse:10'], 'uses' => $path->node->methods->get]);
					Route::get('{slug}', ['middleware'=>['cacheResponse:10'], 'uses'=>$path->node->methods->get])->where('slug', '([A-z\d-\/_.]+)?');
				}
				else
				{
					Route::get('/', ['uses' => $path->node->methods->get]);
					Route::get('{slug}', ['uses'=>$path->node->methods->get])->where('slug', '([A-z\d-\/_.]+)?');
				}
				
			}

			if($path->node->methods->post)
			{
				Route::post('/', ['uses' => $path->node->methods->post]);
				Route::post('{slug}', ['uses'=>$path->node->methods->post])->where('slug', '([A-z\d-\/_.]+)?');
			}

			if($path->node->methods->patch)
			{
				Route::patch('/', ['uses' => $path->node->methods->patch]);
				Route::patch('{slug}', ['uses'=>$path->node->methods->patch])->where('slug', '([A-z\d-\/_.]+)?');
			}

			if($path->node->methods->delete)
			{
				Route::delete('/', ['uses' => $path->node->methods->delete]);
				Route::delete('{slug}', ['uses'=>$path->node->methods->delete])->where('slug', '([A-z\d-\/_.]+)?');
			}

			if($path->node->model->methods->get)
			{
				if($path->node->model->settings->use_response_cache or $path->node->settings->use_response_cache)
				{
					Route::get('/', ['middleware'=>['cacheResponse:10'], 'uses' => $path->node->model->methods->get]);
					Route::get('{slug}', ['middleware'=>['cacheResponse:10'], 'uses'=>$path->node->model->methods->get])->where('slug', '([A-z\d-\/_.]+)?');
				}
				else
				{
					Route::get('/', ['uses' => $path->node->model->methods->get]);
					Route::get('{slug}', ['uses'=>$path->node->model->methods->get])->where('slug', '([A-z\d-\/_.]+)?');
				}
				
			}

			if($path->node->model->methods->post)
			{
				Route::post('/', ['uses' => $path->node->model->methods->post]);
				Route::post('{slug}', ['uses'=>$path->node->model->methods->post])->where('slug', '([A-z\d-\/_.]+)?');
			}

			if($path->node->model->methods->patch)
			{
				Route::patch('/', ['uses' => $path->node->model->methods->patch]);
				Route::patch('{slug}', ['uses'=>$path->node->model->methods->patch])->where('slug', '([A-z\d-\/_.]+)?');
			}

			if($path->node->model->methods->delete)
			{
				Route::delete('/', ['uses' => $path->node->model->methods->delete]);
				Route::delete('{slug}', ['uses'=>$path->node->model->methods->delete])->where('slug', '([A-z\d-\/_.]+)?');
			}
		}
	});
}

