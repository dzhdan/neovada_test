<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;


class Product extends \app\models\base\Product
{
    public function deleteProduct()
    {
        $this->deleted = true;
        return $this->save();
    }

    /**
     * @param $products
     * @return array
     */
    public static function getLabels($products)
    {
        return ArrayHelper::map($products, 'id', 'title');
    }
}
