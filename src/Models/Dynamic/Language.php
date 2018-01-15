<?php 

namespace Runsite\CMF\Models\Dynamic;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Language extends Eloquent
{
    protected $table = 'rs_languages';
    protected $fillable = ['locale', 'display_name', 'is_active'];
}
