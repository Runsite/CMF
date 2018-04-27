<?php 

Route::group(['prefix'=>'admin/api/ckeditor', 'as'=>'api.ckeditor.', 'namespace'=>'Runsite\CMF\Http\Controllers\Api\Ckeditor'], function() {
    Route::any('images-upload', ['as'=>'images-upload', 'uses'=>'ImagesController@upload']);
});

Route::group(['prefix' => (config('app.env') === 'testing' ? config('app.fallback_locale') : LaravelLocalization::setLocale()).'/'.config('runsite.cmf.admin_dirname'), 'namespace' => 'Runsite\CMF\Http\Controllers', 'middleware'=>['check-admin-ip', 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath', 'web']], function() {

    Auth::routes();

    Route::group(['prefix'=>'invite', 'as'=>'admin.invite.', 'namespace'=>'Users'], function() {
        Route::get('register/{token}', ['as'=>'form', 'uses'=>'InviteController@form']);
        Route::patch('register/{token}', ['as'=>'register', 'uses'=>'InviteController@register']);
    });
    

    Route::group(['middleware' => ['auth', 'abort-if-locked'], 'as' => 'admin.'], function() {

        Route::get('/', function() {
            return view('runsite::boot');
        })->name('boot');

        // Api
        Route::group(['prefix'=>'api', 'as'=>'api.', 'namespace'=>'Api'], function() {
            Route::group(['prefix'=>'node', 'as'=>'node.'], function() {
                Route::get('find-by-name', ['as'=>'find-by-name', 'uses'=>'NodesController@findByName']);
                Route::get('inner-link', ['as'=>'inner-link', 'uses'=>'NodesController@innerLink']);
            });

            Route::get('sound-notification-count', ['as'=>'sound-notification-count', 'uses'=>'NotificationsController@soundNotificationsCount']);

            Route::get('enable-notifications-sound', ['as'=>'enable-notifications-sound', 'uses'=>'NotificationsController@enableNotificationsSound']);

            Route::get('disable-notifications-sound', ['as'=>'disable-notifications-sound', 'uses'=>'NotificationsController@disableNotificationsSound']);

        });

        Route::group(['prefix'=>'notifications', 'as'=>'notifications.', 'namespace'=>'Notifications'], function() {
            Route::get('/', ['as'=>'index', 'uses'=>'NotificationsController@index']);
            Route::get('{notification}', ['as'=>'show', 'uses'=>'NotificationsController@show']);
        });

        Route::group(['prefix'=>'search', 'as'=>'search.', 'namespace'=>'Search'], function() {
            Route::get('/{search_key}', ['as'=>'find-model', 'uses'=>'SearchController@findModel']);
            Route::get('/{search_key}/{model}', ['as'=>'show', 'uses'=>'SearchController@show']);
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

                    Route::patch('{model}/make-model-searchable', ['as'=>'make_model_searchable', 'uses'=>'SettingsController@makeModelSearchable'])->middleware('application-access:models:edit');
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
                    Route::get('{model}/methods/{controller}',   ['as'=>'controller',   'uses'=>'MethodsController@controller']);
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
                    Route::post('{model}/fields/by-template/{template_id}', ['as'=>'store_by_template', 'uses'=>'FieldsController@storeByTemplate'])
                        ->middleware('application-access:models:edit');

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

            Route::group(['prefix'=>'groups', 'as'=>'groups.access.'], function() {
                // MODEL ACCESS
                Route::get('{group}/access',    ['as'=>'edit',   'uses'=>'GroupAccessController@edit']);
                Route::patch('{group}/access',  ['as'=>'update', 'uses'=>'GroupAccessController@update'])
                    ->middleware('application-access:users:edit');
            });

            Route::group(['prefix'=>'users/invite', 'as'=>'users.invite.'], function() {
                Route::get('create', ['as'=>'create', 'uses'=>'InviteController@create']);
                Route::get('show/{invite}', ['as'=>'show', 'uses'=>'InviteController@show']);
                Route::post('/', ['as'=>'store', 'uses'=>'InviteController@store']);
                Route::delete('destroy/{id}', ['as'=>'destroy', 'uses'=>'InviteController@destroy']);
            });
        });

        Route::group(['namespace'=>'Translation', 'prefix'=>'translations', 'as'=>'translations.', 'middleware'=>['application-access:translations:read']], function() {
            Route::get('/', ['as'=>'index', 'uses'=>'TranslationsController@index']);
            Route::get('/edit/{translation}', ['as'=>'edit', 'uses'=>'TranslationsController@edit']);
            Route::patch('/update/{translation}', ['as'=>'update', 'uses'=>'TranslationsController@update']);
        });

        Route::group(['namespace'=>'Language', 'middleware'=>['application-access:languages:read']], function() {
            Route::resource('languages', 'LanguagesController');
        });

        Route::group(['prefix'=>'nodes', 'namespace'=>'Nodes', 'as'=>'nodes.', 'middleware'=>['application-access:nodes:read']], function() {
            Route::get('{model}/{parent_id}/create', ['as'=>'create', 'uses'=>'NodesController@create']);
            Route::post('{model}/{parent_node}/store', ['as'=>'store', 'uses'=>'NodesController@store'])->middleware('application-access:nodes:edit');
            Route::get('{node}/edit/{depended_model_id?}', ['as'=>'edit', 'uses'=>'NodesController@edit']);
            Route::patch('{node}/update', ['as'=>'update', 'uses'=>'NodesController@update'])->middleware('application-access:nodes:edit');
            Route::delete('{node}/destroy', ['as'=>'destroy', 'uses'=>'NodesController@destroy'])->middleware('application-access:nodes:delete');
            Route::get('{node}/qr-code/{language}', ['as'=>'qr-code', 'uses'=>'NodesController@qrCode']);

            Route::group(['prefix'=>'move', 'as'=>'move.'], function(){
                Route::patch('{node}/{depended_model_id}/up', ['as'=>'up', 'uses'=>'NodesController@moveUp'])
                    ->middleware('application-access:nodes:edit');
                Route::patch('{node}/{depended_model_id}/down', ['as'=>'down', 'uses'=>'NodesController@moveDown'])
                    ->middleware('application-access:nodes:edit');
            });

            Route::group(['prefix'=>'settings', 'as'=>'settings.', 'namespace'=>'Settings'], function() {

                Route::get('{node}',      ['as'=>'edit',   'uses'=>'SettingsController@edit']);
                Route::patch('{node}',    ['as'=>'update', 'uses'=>'SettingsController@update'])->middleware('application-access:nodes:edit');

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

                Route::group(['as'=>'access.'], function() {
                    // NODE ACCESS
                    Route::get('{node}/access',    ['as'=>'edit',   'uses'=>'AccessController@edit']);
                    Route::patch('{node}/access',  ['as'=>'update', 'uses'=>'AccessController@update'])
                        ->middleware('application-access:nodes:edit');
                });

                Route::group(['as'=>'methods.'], function() {
                    // MODEL METHODS
                    Route::get('{node}/methods',   ['as'=>'edit',   'uses'=>'MethodsController@edit']);
                    Route::get('{node}/methods/{controller}',   ['as'=>'controller',   'uses'=>'MethodsController@controller']);
                    Route::patch('{node}/methods', ['as'=>'update', 'uses'=>'MethodsController@update'])
                        ->middleware('application-access:nodes:edit');
                });
            });
        });

        Route::group(['prefix'=>'elfinder'], function() {
            Route::get('/',  ['as' => 'elfinder.index', 'uses' =>'\Barryvdh\Elfinder\ElfinderController@showIndex'])->middleware('application-access:elfinder:read');
            Route::any('connector', ['as' => 'elfinder.connector', 'uses' => '\Barryvdh\Elfinder\ElfinderController@showConnector'])->middleware('application-access:elfinder:read');
            Route::get('popup/{input_id}', ['as' => 'elfinder.popup', 'uses' => '\Barryvdh\Elfinder\ElfinderController@showPopup'])->middleware('application-access:elfinder:read');
            Route::get('filepicker/{input_id}', ['as' => 'elfinder.filepicker', 'uses' => '\Barryvdh\Elfinder\ElfinderController@showFilePicker'])->middleware('application-access:elfinder:read');
            Route::get('tinymce', ['as' => 'elfinder.tinymce', 'uses' => '\Barryvdh\Elfinder\ElfinderController@showTinyMCE'])->middleware('application-access:elfinder:read');
            Route::get('tinymce4', ['as' => 'elfinder.tinymce4', 'uses' => '\Barryvdh\Elfinder\ElfinderController@showTinyMCE4'])->middleware('application-access:elfinder:read');
            Route::get('ckeditor', ['as' => 'elfinder.ckeditor', 'uses' => '\Barryvdh\Elfinder\ElfinderController@showCKeditor4'])->middleware('application-access:elfinder:read');
        });

    });

});
