<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Author $model */

$this->title = 'Edit Author: ' . $model->full_name;
$this->params['breadcrumbs'][] = ['label' => 'Authors', 'url' => ['/author/index']];
$this->params['breadcrumbs'][] = ['label' => $model->full_name, 'url' => ['/author/view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Edit';
?>
<div class="author-update">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', ['model' => $model]) ?>
</div>
