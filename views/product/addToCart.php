<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use \app\models\Product;
/* @var $this yii\web\View */
/* @var $model app\models\forms\AddToCartForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-form">
    <?php $form = ActiveForm::begin(
        [
            'id' => 'add-product',
            'enableAjaxValidation' => true,
            'validationUrl' => Url::to(['/product/validate', 'id' => $model->product_id])
        ]); ?>

    <h4><?= $model->product_title?></h4>

    <?= $form->field($model, 'count')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('<span class="glyphicon glyphicon-plus"></span>Add', ['class' => 'btn btn-primary']) ?>
    </div>

    <h4><?= $model->total_quantity?></h4>

    <?php ActiveForm::end(); ?>

</div>

