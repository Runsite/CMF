<?php 

namespace Runsite\CMF\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Application extends Eloquent
{
	protected $table = 'rs_applications';
	protected $fillable = ['name', 'is_tool', 'color_name'];
}
