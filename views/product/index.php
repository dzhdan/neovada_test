<?php

use app\models\User;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$modalId = 'product-modal';

$this->title = 'Products';
$this->params['breadcrumbs'][] = $this->title;

echo Modal::widget(['id' => $modalId])
?>
<div class="product-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->user->isGuest) : ?>
        <h3>Please login to be able to create orders</h3>
    <?php endif ;?>

    <?php if (Yii::$app->user->can(User::ROLE_ADMIN)) :?>
    <p>
        <?= Html::a('Create Product', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php endif ; ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
            'count',
            [
                'attribute' => 'price',
                'value' => function($item) {
                    return $item->price . ' $';
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Actions',
                'template' => '{view} {update} {popularity} {delete} {add-to-cart} ',
                'visible' => !Yii::$app->user->isGuest,
                'buttons' => [
                    'view' => function ($url, $item) {
                        if (Yii::$app->user->can(User::ROLE_ADMIN)) {
                            $url = Url::to(['/product/view/', 'id' => $item->id]);
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                'title' => 'VIew',
                            ]);
                        }
                    },
                    'update' => function ($url, $item) {
                        if (Yii::$app->user->can(User::ROLE_ADMIN)) {
                            $url = Url::to(['/product/update', 'id' => $item->id]);
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                'title' => 'Update product',
                            ]);
                        }
                    },
                    'add-to-cart' => function ($url, $item) {
                         if (Yii::$app->user->can(User::ROLE_USER)) {
                             $url = Url::to(['/product/add-to-cart', 'id' => $item->id]);
                             return Html::a('<span class="glyphicon glyphicon-plus"></span>', $url, [
                                 'class' => 'add-product',
                                 'title' => 'Add To Cart',
                             ]);
                         }
                    },
                    'popularity' => function ($url, $item) {
                        if (Yii::$app->user->can(User::ROLE_ADMIN)) {
                            $url = Url::to(['/statistic/popularity', 'id' => $item->id]);
                            return Html::a('<span class="glyphicon glyphicon-star"></span>', $url, [
                                'title' => 'Popularity of product',
                            ]);
                        }
                    },
                    'delete' => function ($url, $item) {
                        if (Yii::$app->user->can(User::ROLE_ADMIN)) {
                            $url = Url::to(['/product/delete', 'id' => $item->id]);
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                'class' => 'delete-product',
                                'title' => 'Delete product',
                                'data' => [
                                    'confirm' => 'Are you sure you want to delete the product?',
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

<script>
$(document).ready(function(){
    $('.add-product').on('click', function() {
        var self = $(this),
            $modal = $('#<?= $modalId ?>'),
            modal = $modal.data('bs.modal');

        $.get(self.is('a') ? self.attr('href') : self.data('href'), function(response) {
            console.log(response)
            modal.$element.find('.modal-body').html(response);
            modal.show();
        });

        return false;
    })
    $('body').on('beforeSubmit', '#add-product', function () {
        var form = $(this);

        var $modal = $('#product-modal'),
            modal = $modal.data('bs.modal');

        $.ajax({
            url: form.attr('action'),
            type: 'post',
            data: form.serialize(),
            success: function () {

            }
        });
        modal.hide();

        return false;
    });
})




</script>