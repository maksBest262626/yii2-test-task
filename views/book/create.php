<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Book $model */
/** @var app\models\Author[] $authors */

$this->title = 'Add Book';
$this->params['breadcrumbs'][] = ['label' => 'Books', 'url' => ['/book/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', ['model' => $model, 'authors' => $authors]) ?>
</div>
