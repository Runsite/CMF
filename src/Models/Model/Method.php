<?php 

namespace Runsite\CMF\Models\Model;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Method extends Eloquent
{
    protected $table = 'rs_model_methods';
    protected $fillable = ['model_id', 'get', 'post', 'patch', 'delete'];

    public function model()
    {
        return $this->belongsTo(Model::class, 'model_id');
    }
}