<?php

namespace Runsite\CMF\Models\Node;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Runsite\CMF\Models\Model\Model;
use Runsite\CMF\Models\Dynamic\Dynamic;
use Runsite\CMF\Models\Dynamic\Language;
use Runsite\CMF\Models\User\Group;
use Runsite\CMF\Models\User\Access\AccessNode;
use DB;
use LaravelLocalization;
use Goszowski\Temp\Temp;

class Node extends Eloquent
{
    protected $table = 'rs_nodes';
    protected $fillable = ['parent_id', 'model_id', 'position'];
    public $nodeWithData = null;

    public function path()
    {
        return $this->hasOne(Path::class, 'node_id')->orderby('id', 'desc');
    }

    public function paths()
    {
        return $this->hasMany(Path::class, 'node_id');
    }

    public function model()
    {
        return $this->belongsTo(Model::class);
    }

    public function parent()
    {
        return $this->belongsTo(Node::class, 'parent_id');
    }

    public function methods()
    {
        return $this->hasOne(Method::class, 'node_id');
    }

    public function dependencies()
    {
        return $this->belongsToMany(Model::class, 'rs_node_dependencies', 'node_id', 'depended_model_id');
    }

    public function putAnalytic($type)
    {
        Analytic::create(['model_id'=>$this->model->id, 'parent_node_id'=>$this->parent_id, 'type'=>$type]);
    }

    public static function getNewPosition(array $attributes = [])
    {
        $lastPosition =  Node::where('parent_id', $attributes['parent_id'])->where('model_id', $attributes['model_id'])->orderBy('position', 'desc')->first();
        if($lastPosition)
        {
            return $lastPosition->position + 1;
        }

        return 1;
    }

    public static function create(array $attributes = [], $basename = null)
    {
        $attributes['position'] = self::getNewPosition($attributes);
        $node = parent::query()->create($attributes);

        $groups = Group::get();
        foreach($groups as $group)
        {
            AccessNode::create([
                'group_id' => $group->id,
                'node_id' => $node->id,
                'access' => 3,
            ]);
        }

        Method::create(['node_id'=>$node->id]);
        
        $node->putAnalytic(1);

        $languages = Language::get();

        foreach($languages as $language)
        {
            Path::create([
                'node_id' => $node->id,
                'language_id' => $language->id,
                'name' => $node->generatePath($basename),
            ]);

            DB::table($node->model->tableName())->insert([
                'node_id' => $node->id,
                'language_id' => $language->id,
                'created_at' => $node->created_at,
                'updated_at' => $node->updated_at,
            ]);
        }

        $output = new \stdClass;
        foreach($languages as $language)
        {
            $dynamic = new Dynamic($node->model->tableName());
            $output->{$language->locale} = $dynamic->where('language_id', $language->id)->where('node_id', $node->id)->first();
        }

        $output->baseNode = $node;

        return $output;

    }

    public function generatePath($basename=null, $unique=true)
    {
        if($this->parent_id === null)
        {
            $basename = null;
        }

        $root = $this->parent ? $this->parent->path->name . '/' : '/';

        if($root == '//')
        {
            $root = '/';
        }

        $path = $root . str_slug($basename);

        if($this->parent_id and $unique)
        {
            while(Path::where('rs_paths.name', $path)->join('rs_nodes', 'rs_nodes.id', '=', 'rs_paths.node_id')->where('rs_nodes.parent_id', $this->parent_id)->count() >= Language::count())
            {
                $path .= Node::where('parent_id', $this->parent_id)->count();
            }
        }
        

        return $path;
    }

    public function dynamic()
    {
        $modelName = 'App\Models\\'. $this->model->className();
        return (new $modelName($this->model->tableName()))->where('node_id', $this->id);
    }

    public function dynamicCurrentLanguage()
    {
        $language = Temp::get('current-language') ?: Temp::put('current-language', Language::where('locale', LaravelLocalization::getCurrentLocale())->first());
        return $this->dynamic()->where('language_id', $language->id);
    }

    public function delete()
    {
        foreach($this->model->fields as $field)
        {
            $field->beforeDeleting($this);
        }

        // Calculating position
        Node::where('parent_id', $this->parent_id)->where('model_id', $this->model_id)->where('position', '>', $this->position)->decrement('position');

        return parent::delete();
    }

    public function moveDown()
    {
        Node::where('parent_id', $this->parent_id)->where('model_id', $this->model_id)->where('position', '>', $this->position)->orderBy('position', 'asc')->first()->decrement('position');
        $this->increment('position');
    }

    public function moveUp()
    {
        Node::where('parent_id', $this->parent_id)->where('model_id', $this->model_id)->where('position', '<', $this->position)->orderBy('position', 'desc')->first()->increment('position');
        $this->decrement('position');
    }

    public function breadcrumbs()
    {
        $result = [];

        if($this->id > 1)
        {
            $result[1] = $this;
        }

        if($this->parent_id > 1)
        {
            $result[0] = Node::findOrFail($this->parent_id);
        }

        ksort($result);

        return $result;
    }

    public function breadcrumbsBetweenRoot()
    {
        if(Temp::exists('breadcrumbs-between-root'))
        {
            return Temp::get('breadcrumbs-between-root');
        }
        else
        {
            $result = [];

            if($this->parent_id > 1)
            {
                $parent = Node::findOrFail($this->parent_id);

                while($parent->parent_id > 1)
                {
                    $parent = Node::findOrFail($parent->parent_id);
                    $result[] = $parent;
                }
            }

            return Temp::put('breadcrumbs-between-root', $result);
        }
    }
}
