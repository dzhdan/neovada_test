<?php

use app\models\User;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'buyer_id',
                'visible' => Yii::$app->user->can(User::ROLE_ADMIN),
                'value' => function($item) {
                    return $item->buyer->email;
                }
            ],
            [
                'attribute' =>   'created_at',
                'filter' => false,
                'value' => function($item) {
                    return date('d/m/Y', strtotime($item->created_at));
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {delete}',
                'buttons' => [
                    'view' => function ($url, $item) {
                        $url = Url::to(['/order/view/', 'id' => $item->id]);
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                            'title' => 'View Order',
                        ]);
                    },
                    'delete' => function ($url, $item) {
                        if (Yii::$app->user->can(User::ROLE_ADMIN)) {
                            $url = Url::to(['/order/delete', 'id' => $item->id]);
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                'title' => 'Delete order',
                                'data' => [
                                    'confirm' => 'Are you sure you want to delete the order?',
                                    'method' => 'post',
                                ]
                            ]);
                        }
                    },
                ],
            ],
        ],
    ]); ?>

</div>
