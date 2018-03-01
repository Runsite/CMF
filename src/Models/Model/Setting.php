<?php 

namespace Runsite\CMF\Models\Model;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Setting extends Eloquent
{
    protected $table = 'rs_model_settings';
    protected $fillable = ['model_id', 'show_in_admin_tree', 'use_response_cache', 'slug_autogeneration', 'nodes_ordering', 'dynamic_model', 'max_nodes_count'];

    public function model()
    {
        return $this->belongsTo(Model::class, 'model_id');
    }
}
