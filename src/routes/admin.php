<?php 

Route::group(['prefix' => (config('app.env') === 'testing' ? config('app.fallback_locale') : LaravelLocalization::setLocale()).'/admin', 'namespace' => 'Runsite\CMF\Http\Controllers', 'middleware'=>['localeSessionRedirect', 'localizationRedirect', 'localeViewPath', 'web']], function() {

    Auth::routes();

    Route::group(['middleware' => 'auth', 'as' => 'admin.'], function() {

        Route::get('/', function() {
            return view('runsite::boot');
        })->name('boot');

        // Api
        Route::group(['prefix'=>'api', 'as'=>'api.', 'namespace'=>'Api'], function() {
            Route::group(['prefix'=>'node', 'as'=>'node.'], function() {
                Route::get('find-by-name', ['as'=>'find-by-name', 'uses'=>'NodesController@findByName']);
            });
        });

        // Account
        Route::group(['prefix'=>'account', 'as'=>'account.', 'namespace'=>'Account'], function() {

            // Settings
            Route::group(['prefix'=>'settings', 'as'=>'settings.'], function() {
                Route::get('/')->name('edit')->uses('SettingsController@edit');
                Route::patch('update')->name('update')->uses('SettingsController@update');
            });

            // Cropping image
            Route::group(['prefix'=>'image', 'as'=>'image.'], function() {
                Route::get('/')->name('edit')->uses('ImageController@edit');
                Route::patch('/')->name('crop')->uses('ImageController@crop');
            });

            // Needs rehash
            Route::group(['prefix'=>'rehash', 'as'=>'rehash.'], function() {
                Route::get('/')->name('form')->uses('SettingsController@needsRehash');
                Route::patch('/')->name('rehash')->uses('SettingsController@rehash');
            });

        });

        // Apps 
        Route::group(['namespace'=>'Model', 'middleware'=>['application-access:models:read']], function() {
            Route::resource('models', 'ModelsController');
            Route::group(['prefix'=>'models', 'as'=>'models.'], function() {

                Route::group(['as'=>'settings.'], function() {
                    // MODEL SETTINGS
                    Route::get('{model}/settings',      ['as'=>'edit',   'uses'=>'SettingsController@edit']);
                    Route::patch('{model}/settings',    ['as'=>'update', 'uses'=>'SettingsController@update'])
                        ->middleware('application-access:models:edit');
                });

                Route::group(['as'=>'dependencies.'], function() {
                    // MODEL DEPENDENCIES
                    Route::get('{model}/dependencies',      ['as'=>'index',  'uses'=>'DependenciesController@index']);
                    Route::post('{model}/dependencies',     ['as'=>'store',  'uses'=>'DependenciesController@store'])
                        ->middleware('application-access:models:edit');
                    Route::delete('{model}/dependencies',   ['as'=>'delete', 'uses'=>'DependenciesController@destroy'])
                        ->middleware('application-access:models:delete');

                    Route::group(['prefix'=>'move', 'as'=>'move.'], function() {
                        Route::patch('{model}/dependencies/{depended_model_id}/up',     ['as'=>'up',   'uses'=>'DependenciesController@moveUp'])
                            ->middleware('application-access:models:edit');
                        Route::patch('{model}/dependencies/{depended_model_id}/down',   ['as'=>'down', 'uses'=>'DependenciesController@moveDown'])
                            ->middleware('application-access:models:edit');
                    });
                });

                Route::group(['as'=>'access.'], function() {
                    // MODEL ACCESS
                    Route::get('{model}/access',    ['as'=>'edit',   'uses'=>'AccessController@edit']);
                    Route::patch('{model}/access',  ['as'=>'update', 'uses'=>'AccessController@update'])
                        ->middleware('application-access:models:edit');
                });

                Route::group(['as'=>'methods.'], function() {
                    // MODEL METHODS
                    Route::get('{model}/methods',   ['as'=>'edit',   'uses'=>'MethodsController@edit']);
                    Route::patch('{model}/methods', ['as'=>'update', 'uses'=>'MethodsController@update'])
                        ->middleware('application-access:models:edit');
                });

                Route::group(['as'=>'fields.', 'namespace'=>'Fields'], function() {
                    // MODEL FIELDS
                    Route::get('{model}/fields',                    ['as'=>'index',   'uses'=>'FieldsController@index']);
                    Route::get('{model}/fields/create',             ['as'=>'create',  'uses'=>'FieldsController@create'])
                        ->middleware('application-access:models:edit');
                    Route::get('{model}/fields/{field}/edit',       ['as'=>'edit',    'uses'=>'FieldsController@edit']);
                    Route::post('{model}/fields/', ['as'=>'store', 'uses'=>'FieldsController@store'])
                        ->middleware('application-access:models:edit');
                    Route::patch('{model}/fields/{field}/update',   ['as'=>'update',  'uses'=>'FieldsController@update'])
                        ->middleware('application-access:models:edit');
                    Route::delete('{model}/fields/{field}/destroy', ['as'=>'destroy', 'uses'=>'FieldsController@destroy'])
                        ->middleware('application-access:models:delete');

                    Route::group(['as'=>'settings.'], function() {
                        // MODEL FIELD SETTINGS
                        Route::get('{model}/fields/{field}/settings', ['as'=>'edit', 'uses'=>'SettingsController@edit']);
                        Route::patch('{model}/fields/{field}/settings', ['as'=>'update', 'uses'=>'SettingsController@update'])
                            ->middleware('application-access:models:edit');
                    });

                    Route::group(['as'=>'access.'], function() {
                        // MODEL FIELD ACCESS
                        Route::get('{model}/fields/{field}/access', ['as'=>'edit', 'uses'=>'AccessController@edit']);
                        Route::patch('{model}/fields/{field}/access', ['as'=>'update', 'uses'=>'AccessController@update'])
                            ->middleware('application-access:models:edit');
                    });

                    Route::group(['prefix'=>'move', 'as'=>'move.'], function() {
                        // MODEL FIELD MOVE
                        Route::patch('{model}/fields/{field}/up', ['as'=>'up', 'uses'=>'FieldsController@moveUp'])
                            ->middleware('application-access:models:edit');
                        Route::patch('{model}/fields/{field}/down', ['as'=>'down', 'uses'=>'FieldsController@moveDown'])
                            ->middleware('application-access:models:edit');
                    });
                });

                Route::group(['as'=>'groups.', 'namespace'=>'Fields'], function() {
                    // MODEL GROUPS
                    Route::get('{model}/groups', ['as'=>'index', 'uses'=>'FieldGroupsController@index']);
                    Route::get('{model}/groups/create', ['as'=>'create', 'uses'=>'FieldGroupsController@create'])
                        ->middleware('application-access:models:edit');
                    Route::get('{model}/groups/{group}/edit', ['as'=>'edit', 'uses'=>'FieldGroupsController@edit'])
                        ->middleware('application-access:models:edit');
                    Route::post('{model}/groups/', ['as'=>'store', 'uses'=>'FieldGroupsController@store'])
                        ->middleware('application-access:models:edit');
                    Route::patch('{model}/groups/{group}/update', ['as'=>'update', 'uses'=>'FieldGroupsController@update'])
                        ->middleware('application-access:models:edit');
                    Route::delete('{model}/groups/{group}/destroy', ['as'=>'destroy', 'uses'=>'FieldGroupsController@destroy'])
                        ->middleware('application-access:models:delete');

                    Route::group(['prefix'=>'move', 'as'=>'move.'], function() {
                        // MODEL GROUP MOVE
                        Route::patch('{model}/groups/{group}/up', ['as'=>'up', 'uses'=>'FieldGroupsController@moveUp'])
                            ->middleware('application-access:models:edit');
                        Route::patch('{model}/groups/{group}/down', ['as'=>'down', 'uses'=>'FieldGroupsController@moveDown'])
                            ->middleware('application-access:models:edit');
                    });
                });

            });
        });

        Route::group(['namespace'=>'Users'], function() {
            Route::resource('users', 'UsersController');
            Route::resource('groups', 'GroupsController');
        });

        Route::group(['prefix'=>'nodes', 'namespace'=>'Nodes', 'as'=>'nodes.', 'middleware'=>['application-access:nodes:read']], function() {
            Route::get('{model}/{parent_id}/create', ['as'=>'create', 'uses'=>'NodesController@create']);
            Route::post('{model}/{parent_node}/store', ['as'=>'store', 'uses'=>'NodesController@store'])->middleware('application-access:nodes:edit');
            Route::get('{node}/edit/{depended_model_id?}', ['as'=>'edit', 'uses'=>'NodesController@edit']);
            Route::patch('{node}/update', ['as'=>'update', 'uses'=>'NodesController@update'])->middleware('application-access:nodes:edit');
            Route::delete('{node}/destroy', ['as'=>'destroy', 'uses'=>'NodesController@destroy'])->middleware('application-access:nodes:delete');

            Route::group(['prefix'=>'move', 'as'=>'move.'], function(){
                Route::patch('{node}/{depended_model_id}/up', ['as'=>'up', 'uses'=>'NodesController@moveUp'])
                    ->middleware('application-access:nodes:edit');
                Route::patch('{node}/{depended_model_id}/down', ['as'=>'down', 'uses'=>'NodesController@moveDown'])
                    ->middleware('application-access:nodes:edit');
            });

            Route::group(['prefix'=>'settings', 'as'=>'settings.', 'namespace'=>'Settings'], function() {

                Route::group(['prefix'=>'paths', 'as'=>'paths.'], function() {
                    Route::get('{node}', ['as'=>'index', 'uses'=>'PathsController@index']);
                    Route::patch('{node}', ['as'=>'update', 'uses'=>'PathsController@update']);
                });

                Route::group(['prefix'=>'dependencies', 'as'=>'dependencies.'], function() {
                    Route::get('{node}', ['as'=>'index',  'uses'=>'DependenciesController@index']);
                    Route::post('{node}', ['as'=>'store',  'uses'=>'DependenciesController@store']);
                    Route::delete('{node}', ['as'=>'delete', 'uses'=>'DependenciesController@destroy']);

                    Route::group(['prefix'=>'move', 'as'=>'move.'], function() {
                        Route::patch('{node}/{depended_model_id}/up', ['as'=>'up',   'uses'=>'DependenciesController@moveUp']);
                        Route::patch('{node}/{depended_model_id}/down', ['as'=>'down', 'uses'=>'DependenciesController@moveDown']);
                    });
                });
            });
        });



    });

});