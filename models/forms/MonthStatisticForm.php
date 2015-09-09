<?php

namespace app\models\forms;

use app\helpers\MonthHelper;
use app\models\Product;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class MonthStatisticForm extends Model
{
    public $year;
    public $startYear = 2012;

    public function init()
    {
        $this->year = date('Y');
    }

    /**
     * @param $params
     * @return array
     */
    public function search($params)
    {
        if (ArrayHelper::getValue($params, 'year')) {
            $this->year = $params['year'];
        }

        /*
            SELECT sum(oi.count * p.price) as sum, DATE_FORMAT(o.created_at,'%M') as month
                FROM orders as o
            JOIN order_items oi ON(o.id = oi.order_id)
            JOIN products p ON(oi.product_id = p.id)
            WHERE YEAR(o.created_at) = :year
            GROUP BY MONTH(o.created_at);
         */

        $statistics = (new Query())
            ->select([
                'sum(oi.count * p.price) as sum',
                "MONTH(o.created_at) as month"
            ])
            ->from('{{%orders}} o')
            ->innerJoin('{{%order_items}} oi', 'o.id = oi.order_id')
            ->innerJoin('{{%products}} p', 'oi.product_id = p.id')
            ->where('YEAR(o.created_at) = :year', [':year' => $this->year])
            ->groupBy("MONTH(o.created_at)")
            ->all()
        ;

        /*
         SELECT sum(oi.count * p.price) as sum
               FROM orders as o
           JOIN order_items oi ON(o.id = oi.order_id)
           JOIN products p ON(oi.product_id = p.id)
           WHERE YEAR(o.created_at) = :year
       */

        $total = (new Query())
            ->select([
                'sum(oi.count * p.price) as sum',
            ])
            ->from('{{%orders}} o')
            ->innerJoin('{{%order_items}} oi', 'o.id = oi.order_id')
            ->innerJoin('{{%products}} p', 'oi.product_id = p.id')
            ->where('YEAR(o.created_at) = :year', [':year' => $this->year])
            ->scalar();

        $months = MonthHelper::getLabels();

        $data = [];
        foreach ($months as $key => $month) {
            $data[$key] = ['sum' => 0, 'month' => $month];
            foreach ($statistics as $statistic) {
                if ($statistic['month'] == $key) {
                    $data[$key]['sum'] = $statistic['sum'];
                    $data[$key]['month'] = $month;
                    break;
                }
            }
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'sort' => false
        ]);

        return [
            'statistics' => $dataProvider,
            'total' => $total,
        ];
    }

    /**
     * @return array
     */
    public function getYearRange() {
        return array_combine(range($this->startYear, date("Y")), range($this->startYear, date("Y")));
    }
}
