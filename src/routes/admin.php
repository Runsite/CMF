<?php 

Route::group(['prefix' => LaravelLocalization::setLocale().'/admin', 'namespace' => 'Runsite\CMF\Http\Controllers', 'middleware'=>['localeSessionRedirect', 'localizationRedirect', 'localeViewPath', 'web']], function() {

    Auth::routes();

    Route::group(['middleware' => 'auth', 'as' => 'admin.'], function() {

        Route::get('/', function() {
            return view('runsite::boot');
        })->name('boot');

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

        });

        // Apps 
        Route::group(['namespace'=>'Model', 'middleware'=>['application-access:models:read']], function() {
            Route::resource('models', 'ModelsController');
            Route::group(['prefix'=>'models', 'as'=>'models.'], function() {

                Route::get('{model_id}/settings', ['as'=>'settings.edit', 'uses'=>'SettingsController@edit']);
                Route::patch('{model_id}/settings', ['as'=>'settings.update', 'uses'=>'SettingsController@update'])->middleware('application-access:models:edit');

                Route::group(['as'=>'dependencies.'], function() {
                    Route::get('{model_id}/dependencies', ['as'=>'index', 'uses'=>'DependenciesController@index']);
                    Route::post('{model_id}/dependencies', ['as'=>'store', 'uses'=>'DependenciesController@store'])->middleware('application-access:models:edit');
                    Route::delete('{model_id}/dependencies', ['as'=>'delete', 'uses'=>'DependenciesController@destroy'])->middleware('application-access:models:delete');

                    Route::group(['prefix'=>'move', 'as'=>'move.'], function() {
                        Route::patch('{model_id}/dependencies/{dependent_model_id}/up', ['as'=>'up', 'uses'=>'DependenciesController@moveUp'])->middleware('application-access:models:edit');
                        Route::patch('{model_id}/dependencies/{dependent_model_id}/down', ['as'=>'down', 'uses'=>'DependenciesController@moveDown'])->middleware('application-access:models:edit');
                    });
                });

                Route::group(['as'=>'access.'], function() {
                    Route::get('{model_id}/access', ['as'=>'edit', 'uses'=>'AccessController@edit']);
                    Route::patch('{model_id}/access', ['as'=>'update', 'uses'=>'AccessController@update'])->middleware('application-access:models:edit');
                });
                

                Route::get('{model_id}/methods', ['as'=>'methods.edit', 'uses'=>'MethodsController@edit']);
                Route::patch('{model_id}/methods', ['as'=>'methods.update', 'uses'=>'MethodsController@update'])->middleware('application-access:models:edit');

                Route::group(['as'=>'fields.', 'namespace'=>'Fields'], function() {
                    Route::get('{model_id}/fields', ['as'=>'index', 'uses'=>'FieldsController@index']);
                    Route::get('{model_id}/fields/create', ['as'=>'create', 'uses'=>'FieldsController@create'])->middleware('application-access:models:edit');
                    Route::get('{model_id}/fields/{id}/edit', ['as'=>'edit', 'uses'=>'FieldsController@edit']);
                    Route::post('{model_id}/fields/', ['as'=>'store', 'uses'=>'FieldsController@store'])->middleware('application-access:models:edit');
                    Route::patch('{model_id}/fields/{id}/update', ['as'=>'update', 'uses'=>'FieldsController@update'])->middleware('application-access:models:edit');
                    Route::delete('{model_id}/fields/{id}/destroy', ['as'=>'destroy', 'uses'=>'FieldsController@destroy'])->middleware('application-access:models:delete');

                    Route::group(['as'=>'settings.'], function() {
                        Route::get('{model_id}/fields/{field_id}/settings', ['as'=>'edit', 'uses'=>'SettingsController@edit']);
                        Route::patch('{model_id}/fields/{field_id}/settings', ['as'=>'update', 'uses'=>'SettingsController@update'])->middleware('application-access:models:edit');
                    });

                    Route::group(['as'=>'access.'], function() {
                        Route::get('{model_id}/fields/{field_id}/access', ['as'=>'edit', 'uses'=>'AccessController@edit']);
                        Route::patch('{model_id}/fields/{field_id}/access', ['as'=>'update', 'uses'=>'AccessController@update'])->middleware('application-access:models:edit');
                    });

                    Route::group(['prefix'=>'move', 'as'=>'move.'], function() {
                        Route::patch('{model_id}/fields/{field_id}/up', ['as'=>'up', 'uses'=>'FieldsController@moveUp'])->middleware('application-access:models:edit');
                        Route::patch('{model_id}/fields/{field_id}/down', ['as'=>'down', 'uses'=>'FieldsController@moveDown'])->middleware('application-access:models:edit');
                    });
                });

                Route::group(['as'=>'groups.', 'namespace'=>'Fields'], function() {
                    Route::get('{model_id}/groups', ['as'=>'index', 'uses'=>'FieldGroupsController@index']);
                    Route::get('{model_id}/groups/create', ['as'=>'create', 'uses'=>'FieldGroupsController@create'])->middleware('application-access:models:edit');
                    Route::get('{model_id}/groups/{id}/edit', ['as'=>'edit', 'uses'=>'FieldGroupsController@edit'])->middleware('application-access:models:edit');
                    Route::post('{model_id}/groups/', ['as'=>'store', 'uses'=>'FieldGroupsController@store'])->middleware('application-access:models:edit');
                    Route::patch('{model_id}/groups/{id}/update', ['as'=>'update', 'uses'=>'FieldGroupsController@update'])->middleware('application-access:models:edit');
                    Route::delete('{model_id}/groups/{id}/destroy', ['as'=>'destroy', 'uses'=>'FieldGroupsController@destroy'])->middleware('application-access:models:delete');

                    Route::group(['prefix'=>'move', 'as'=>'move.'], function() {
                        Route::patch('{model_id}/groups/{group_id}/up', ['as'=>'up', 'uses'=>'FieldGroupsController@moveUp'])->middleware('application-access:models:edit');
                        Route::patch('{model_id}/groups/{group_id}/down', ['as'=>'down', 'uses'=>'FieldGroupsController@moveDown'])->middleware('application-access:models:edit');
                    });
                });

            });
        });

        Route::group(['namespace'=>'Users'], function() {
            Route::resource('users', 'UsersController');
            Route::resource('groups', 'GroupsController');
        });

        Route::group(['prefix'=>'nodes', 'namespace'=>'Nodes', 'as'=>'nodes.', 'middleware'=>['application-access:nodes:read']], function() {
            Route::get('{model_id}/{parent_id}/create', ['as'=>'create', 'uses'=>'NodesController@create']);
            Route::post('{model_id}/{parent_id}/store', ['as'=>'store', 'uses'=>'NodesController@store'])->middleware('application-access:nodes:edit');
            Route::get('{id}/edit/{depended_model_id?}', ['as'=>'edit', 'uses'=>'NodesController@edit']);
            Route::patch('{id}/update', ['as'=>'update', 'uses'=>'NodesController@update'])->middleware('application-access:nodes:edit');
            Route::delete('{id}/destroy', ['as'=>'destroy', 'uses'=>'NodesController@destroy'])->middleware('application-access:nodes:delete');
        });



    });

});