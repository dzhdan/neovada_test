<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'Cart';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="cart-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php if ($products) :?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'title',
                [
                    'attribute' => 'count',
                    'value' => function($item) use ($products) {
                        return $products[$item['id']]['count'];
                    }
                ]
            ],
        ]); ?>

        <div class="cart-form">
            <div class="form-group">

                <?= Html::a('Checkout', Url::to(['/order/checkout']), ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Remove', Url::to(['/order/clear-cart']), ['class' => 'btn btn-warning']) ?>

            </div>
          </div>
        <?php else : ?>
        Your cart is empty
    <?php endif; ?>

</div>
