<?php 

namespace Runsite\CMF\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Runsite\CMF\Models\Dynamic\Language;

class Translation extends Eloquent
{
    protected $table = 'rs_translations';
    protected $fillable = ['language_id', 'key', 'value'];

    private $variants = null;

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

    public function variants()
    {
        if(! $this->variants)
        {
            $this->variants = Translation::where('key', $this->key)->get();
        }

        return $this->variants;
    }
}
