<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Statistics by month';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="product-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="order-form">

        <?php $form = ActiveForm::begin([
            'method' => 'GET',
            'action' => Url::to(['/statistic/month']),
        ]); ?>

        <div class="row">
            <div class="col-md-4">
                <?= $form->field($monthForm, 'year')->dropDownList($years) ?>
            </div>
            <div class="col-md-4" style="margin-top: 25px">
                <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>

    </div>

    <div class="row">
        <div class="col-md-12">
            <?= GridView::widget([
                'dataProvider' => $statistics,
                'summary' => false,
                'columns' => [
                    'month',
                    [
                        'attribute' => 'sum',
                        'value' => function($item) {
                            return $item['sum'] . ' $';
                        }
                    ]
                ],
            ]); ?>

            <br/>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="pull-right">
                <b>Total:</b> <?=$total?> $
            </div>
        </div>
    </div>
</div>
