<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Order */

$this->title = 'Order number ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->user->can(\app\models\User::ROLE_ADMIN)) : ?>
    <p>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?php endif; ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [

            [
                'attribute' => 'buyer_id',
                'label' => 'Buyer',
                'visible' => Yii::$app->user->can(\app\models\User::ROLE_ADMIN),
                'value' =>  $model->buyer->email,
            ],

            [
                'attribute' => 'crated_at',
                'value' =>  date('d/m/Y', strtotime($model->created_at))
            ]
        ],
    ]) ?>

    <h3>Products</h3>
    <?= GridView::widget([
        'dataProvider' => $orderItemsDataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'product.title',
            'count',
            [
                'attribute' => 'price',
                'value' => function($item) {
                    return $item->product->price . ' $';
                }
            ]
        ],
    ]); ?>

    <div class="row">
        <div class="col-md-12">
            <div class="pull-right">
                <b>Total:</b> <?=$total?> $
            </div>
        </div>
    </div>
</div>
