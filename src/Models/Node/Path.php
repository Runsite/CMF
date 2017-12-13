<?php

namespace Runsite\CMF\Models\Node;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Path extends Eloquent
{
    protected $table = 'rs_paths';
    protected $fillable = ['node_id', 'language_id', 'name'];

    public function node()
    {
        return $this->belongsTo(Node::class, 'node_id');
    }

    public function getRootNameAttribute()
    {
    	$dirname =  dirname($this->name);
    	if($dirname != '/')
    	{
    		$dirname .= '/';
    	}

    	return $dirname;
    }

    public function getBaseNameAttribute()
    {
    	return basename($this->name);
    }
}
