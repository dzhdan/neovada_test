<?php

namespace app\models;

use Yii;
use yii\base\ErrorException;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

class Order extends \app\models\base\Order
{

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @param $buyerId
     * @param $products
     * @return bool
     * @throws \yii\db\Exception
     */
    public static function create($buyerId, $products)
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $order = new self([
                'buyer_id' => $buyerId
            ]);

            if (!$order->save()) {
                throw new ErrorException('Order cannot be saved');
            }

            if ($order) {
                foreach ($products as $id => $product) {
                    $orderItem = new OrderItem([
                        'order_id' => $order->id,
                        'product_id' => $id,
                        'count' => $product['count'],
                    ]);

                    if (!$orderItem->save()) {
                        throw new ErrorException('Order Item cannot be saved');
                    }

                    // Update store  product count;
                    /** @var $product Product*/
                    $product  = Product::findOne(['id' => $id]);
                    $product->count = $product->count - $orderItem->count;

                    if (!$product->save()) {
                        throw new ErrorException('Product cannot be saved');
                    }
                }
            }

            $transaction->commit();
            return $order;

        } catch (\Exception $e) {
            $transaction->rollBack();
            return false;
        }

        return false;
    }
}
