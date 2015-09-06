<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "{{%orders}}".
 *
 * @property integer $id
 * @property integer $deleted
 * @property integer $buyer_id
 * @property string $created_at
 *
 * @property User $buyer
 * @property OrderItem[] $orderItems
 * @property Product[] $products
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%orders}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['deleted', 'buyer_id'], 'integer'],
            [['buyer_id'], 'required'],
            [['created_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'deleted' => 'Deleted',
            'buyer_id' => 'Buyer ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBuyer()
    {
        return $this->hasOne(\app\models\User::className(), ['id' => 'buyer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(\app\models\OrderItem::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(\app\models\Product::className(), ['id' => 'product_id'])->viaTable('{{%order_items}}', ['order_id' => 'id']);
    }
}
