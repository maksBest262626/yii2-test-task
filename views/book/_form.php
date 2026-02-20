<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Book $model */
/** @var app\models\Author[] $authors */
/** @var yii\widgets\ActiveForm $form */

$authorList = \yii\helpers\ArrayHelper::map($authors, 'id', 'full_name');
$selectedAuthorIds = $model->author_ids;
?>

<div class="book-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'year')->textInput(['type' => 'number', 'min' => 1000, 'max' => 9999]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 4]) ?>

    <?= $form->field($model, 'isbn')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'imageFile')->fileInput() ?>

    <?php if ($model->cover_image): ?>
        <div class="mb-3">
            <label class="form-label">Current Cover</label><br>
            <img src="<?= \yii\helpers\Html::encode('/uploads/books/' . $model->cover_image) ?>"
                 alt="Cover" style="max-height: 150px;">
        </div>
    <?php endif ?>

    <?= $form->field($model, 'author_ids')->checkboxList($authorList, [
        'item' => function ($index, $label, $name, $checked, $value) {
            $check = Html::checkbox($name, $checked, ['value' => $value, 'class' => 'form-check-input', 'id' => "author_{$value}"]);
            $lbl = Html::label($label, "author_{$value}", ['class' => 'form-check-label ms-1']);
            return '<div class="form-check">' . $check . $lbl . '</div>';
        },
    ])->label('Authors <span class="text-danger">*</span>', ['encode' => false]) ?>

    <div class="form-group mt-3">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Cancel', $model->isNewRecord ? ['/book/index'] : ['/book/view', 'id' => $model->id], ['class' => 'btn btn-secondary ms-2']) ?>
    </div>

    <?php ActiveForm::end() ?>
</div>
