<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "{{%products}}".
 *
 * @property integer $id
 * @property string $title
 * @property integer $count
 * @property string $price
 * @property integer $deleted
 * @property string $created_at
 *
 * @property OrderItem[] $orderItems
 * @property Order[] $orders
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%products}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['count', 'deleted'], 'integer'],
            [['price'], 'number'],
            [['created_at'], 'safe'],
            [['title'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'count' => 'Count',
            'price' => 'Price',
            'deleted' => 'Deleted',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(\app\models\OrderItem::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(\app\models\Order::className(), ['id' => 'order_id'])->viaTable('{{%order_items}}', ['product_id' => 'id']);
    }
}
