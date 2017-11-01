<?php 

namespace Runsite\CMF\Models\Dynamic;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Runsite\CMF\Models\Node\Node;
use Runsite\CMF\Models\Node\Path;
use Runsite\CMF\Helpers\GlobalScope;
use LaravelLocalization;
use Goszowski\Temp\Temp;

class Dynamic extends Eloquent
{
    protected $table = '';
    protected $fillable = ['node_id', 'language_id'];
    protected $dates = [];

    public function __construct($table, $dates=null)
    {
        parent::__construct();

        $scope = new GlobalScope;

        if($table)
        {
            $this->table = $table;
            $scope->set('DynamicTable', $table);
        }
        elseif($scope->get('DynamicTable'))
        {
            $this->table = $scope->get('DynamicTable');
        }

        if($dates)
        {
            $this->dates = $dates;
        }
    }

    public function node()
    {
        return $this->belongsTo(Node::class, 'node_id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

    public function save(array $options = []): bool
    {
        if($this->name)
        {
            $pathExists = Path::where('node_id', $this->node->id)->where('name', $this->node->generatePath($this->name, false, $this->language_id))->count();

            if(!$pathExists)
            {
                // creating new path
                Path::create([
                    'node_id' => $this->node->id,
                    'language_id' => $this->language_id,
                    'name' => $this->node->generatePath($this->name),
                ]);
            }
        }

        $this->node->putAnalytic(2);

        return parent::save($options);
    }

    public function first(): Dynamic
    {
        // $this->orderBy('rs_nodes.position', 'asc');
        return parent::first();
    }
}