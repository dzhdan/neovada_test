<?php

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
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Product', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

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
                'template' => '{view} {update} {delete} {add-to-cart}',
                'buttons' => [

                    'add-to-cart' => function ($url, $item) {
                        $url = Url::to(['/product/add-to-cart', 'id' => $item->id]);
                        return Html::a('Add to cart', $url, [
                            'class' => 'add-product',
                            'title' => 'Delete',
                            'data-pjax' => 0,
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>

</div>

<script>
    $('.add-product').on('click', function() {
        var self = $(this),
            $modal = $('#<?= $modalId ?>'),
            modal = $modal.data('bs.modal');

        $.get(self.is('a') ? self.attr('href') : self.data('href'), function(response) {
            modal.$element.find('.modal-body').html(response);
            modal.show();
        });

        return false;
    })
</script>