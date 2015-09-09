<?php

namespace app\models\forms;

use app\models\Product;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class PopularityForm extends Model
{

    public function search($productId)
    {
        /*SELECT
            p.title
            sum(oi.count) / s.sum * 100 AS percent,
            EXTRACT(MONTH FROM o.created_at) as month,
            EXTRACT(YEAR FROM o.created_at) as year
        FROM products p
        JOIN order_items oi ON (p.id = oi.product_id)
        JOIN orders o ON o.id = oi.order_id
        INNER JOIN (
               SELECT sum(oi.count) sum
               FROM products p
                 JOIN order_items oi ON (p.id = oi.product_id)
                 JOIN orders o ON o.id = oi.order_id
               WHERE p.id = :id
             ) s
        WHERE p.id = :id
        GROUP BY EXTRACT(month, year);*/

        $subquery = (new Query())
            ->select(['sum(oi.count) sum'])
            ->from('{{%products}} p ')
            ->innerJoin('{{%order_items}} oi', 'p.id = oi.product_id')
            ->innerJoin('{{%orders}} o', 'o.id = oi.order_id')
            ->where(['p.id' => $productId]);

        $query = (new Query())
            ->select([
                'p.title',
                'sum(oi.count) / s.sum * 100 AS percent',
                'EXTRACT(MONTH FROM o.created_at) as month',
                'EXTRACT(YEAR FROM o.created_at) as year'
            ])
            ->from('{{%products}} p')
            ->innerJoin('{{%order_items}} oi', 'p.id = oi.product_id')
            ->innerJoin('{{%orders}} o', 'o.id = oi.order_id')
            ->innerJoin(['s' => $subquery])
            ->where(['p.id' => $productId])
            ->groupBy('month, year')
            ->all();

        return new ArrayDataProvider([
            'allModels' => $query,
        ]);
    }
}
