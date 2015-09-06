<?php

namespace app\modules\gii\generators\model;

use ReflectionClass;
use Yii;
use yii\helpers\Inflector;

class Generator extends \yii\gii\generators\model\Generator
{
    public $ns = 'app\models\base';
    
    public function defaultTemplate()
    {
        $parent = new \yii\gii\generators\model\Generator();

        $class = new ReflectionClass($parent);
        return dirname($class->getFileName()) . '/default';
    }

    public function formView()
    {
        $parent = new \yii\gii\generators\model\Generator();
        
        $class = new ReflectionClass($parent);
        return dirname($class->getFileName()) . '/form.php';
    }

    protected function generateClassName($tableName, $useSchemaName = null)
    {
        $result = parent::generateClassName($tableName, $useSchemaName);

        return Inflector::singularize($result);
    }

    protected function generateRelations()
    {
        $relations = parent::generateRelations();
        
        foreach ($relations as &$relation) {
            foreach ($relation as &$item) {
                $item[0] = str_replace($item[1]  . '::', '\app\models\\' . $item[1] . '::', $item[0]);
            }
            ksort($relation);
        }
        ksort($relations);
        
        return $relations;
    }
}
