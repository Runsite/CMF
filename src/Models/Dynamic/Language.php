<?php 

namespace Runsite\CMF\Models\Dynamic;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Language extends Eloquent
{
	protected $table = 'rs_languages';
	protected $fillable = ['locale', 'display_name', 'is_active', 'is_main'];

	public function hasConfig()
	{
		return (bool) config('laravellocalization.supportedLocales.'.$this->locale);
	}
}
