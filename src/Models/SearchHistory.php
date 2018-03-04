<?php 

namespace Runsite\CMF\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Runsite\CMF\Models\User\User;

class SearchHistory extends Eloquent
{
	protected $table = 'rs_search_history';
	protected $fillable = ['user_id', 'search_key'];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}
}
