<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Book $model */
/** @var app\models\Author[] $authors */

$this->title = 'Edit Book: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Books', 'url' => ['/book/index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['/book/view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Edit';
?>
<div class="book-update">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', ['model' => $model, 'authors' => $authors]) ?>
</div>
