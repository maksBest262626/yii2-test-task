<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Book $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Books', 'url' => ['/book/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-view">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1><?= Html::encode($this->title) ?></h1>
        <?php if (!Yii::$app->user->isGuest): ?>
            <div>
                <?= Html::a('Edit', ['/book/update', 'id' => $model->id], ['class' => 'btn btn-secondary']) ?>
                <?= Html::beginForm(['/book/delete', 'id' => $model->id], 'post', ['style' => 'display:inline']) ?>
                <?= Html::submitButton('Delete', ['class' => 'btn btn-danger ms-1',
                    'onclick' => 'return confirm("Delete this book?")']) ?>
                <?= Html::endForm() ?>
            </div>
        <?php endif ?>
    </div>

    <div class="row">
        <?php if ($model->cover_image): ?>
            <div class="col-md-3">
                <img src="<?= Html::encode('/uploads/books/' . $model->cover_image) ?>"
                     class="img-fluid rounded" alt="Cover">
            </div>
            <div class="col-md-9">
        <?php else: ?>
            <div class="col-md-12">
        <?php endif ?>
            <table class="table table-bordered">
                <tr>
                    <th style="width:150px">Year</th>
                    <td><?= (int)$model->year ?></td>
                </tr>
                <?php if ($model->isbn): ?>
                <tr>
                    <th>ISBN</th>
                    <td><?= Html::encode($model->isbn) ?></td>
                </tr>
                <?php endif ?>
                <tr>
                    <th>Authors</th>
                    <td>
                        <?php foreach ($model->authors as $author): ?>
                            <?= Html::a(Html::encode($author->full_name), ['/author/view', 'id' => $author->id]) ?>
                            <br>
                        <?php endforeach ?>
                    </td>
                </tr>
                <?php if ($model->description): ?>
                <tr>
                    <th>Description</th>
                    <td><?= nl2br(Html::encode($model->description)) ?></td>
                </tr>
                <?php endif ?>
            </table>
        </div>
    </div>

    <p><?= Html::a('&laquo; Back to Books', ['/book/index'], ['class' => 'btn btn-outline-secondary']) ?></p>
</div>
