<?php

namespace app\models\forms;

use app\models\Product;
use Yii;
use yii\base\Model;

class CartForm extends Model
{
    public $product_id;
    public $count;
    public $product_title;
    public $total_quantity;

  /*  public function init()
    {
        $product = Product::findOne(['id' => $this->product_id]);
        $this->product_title = $product->title;
        $this->total_quantity = $product->count;
    }*/

  /*  public function rules()
    {
        return [
            [['product_id', 'count'], 'integer'],
            [['product_id', 'count'], 'required'],
            ['count', 'number', 'min' => 1],
            ['count', 'validateCount'],
        ];
    }*/

}
