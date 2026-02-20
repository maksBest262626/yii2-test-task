<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Author $model */
?>

<div class="author-form">
    <?php $form = ActiveForm::begin() ?>

    <?= $form->field($model, 'full_name')->textInput(['maxlength' => true]) ?>

    <div class="form-group mt-3">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Cancel', $model->isNewRecord ? ['/author/index'] : ['/author/view', 'id' => $model->id], ['class' => 'btn btn-secondary ms-2']) ?>
    </div>

    <?php ActiveForm::end() ?>
</div>
