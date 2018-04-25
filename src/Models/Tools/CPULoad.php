<?php 

namespace Runsite\CMF\Models\Tools;

use Illuminate\Database\Eloquent\Model as Eloquent;

class CPULoad extends Eloquent
{
	protected $table = 'rs_cpu_load';
	protected $fillable = ['value'];
}
