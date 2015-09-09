<?php

use app\helpers\MonthHelper;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Popularity of product: ' . $product->title;
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="popularity">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-12">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'showHeader' => false,
                'summary' => false,
                'columns' => [
                    [
                        'value' => function($item) {
                            return MonthHelper::getLabels()[$item['month']] . ' ' . $item['year'];
                        }
                    ],
                    [
                        'value' => function($item) {
                            return $item['percent']  . " %" ;
                        }
                    ],
                ],
            ]); ?>

            <br/>
        </div>
    </div>

   <div class="row">
        <div class="col-md-12">
            <div class="pull-right">
                <b>Total: 100 %
            </div>
        </div>
    </div>
</div>
